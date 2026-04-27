<?php
// Copyright (C) 2026 Murilo Gomes Julio
// SPDX-License-Identifier: LGPL-2.1-only

// Site: https://mugomes.github.io

namespace MGFormValidation;

class mgformvalidation
{
    private array $data = [];
    private array $rules = [];
    private array $errors = [];

    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function __construct(string|array $data = 'POST')
    {
        if (is_array($data)) {
            $this->data = $data;
        } else {
            $this->data = ($data == 'POST') ? $_POST : $_GET;
        }
    }

    public static function form(string $method, string $action, string $id = '', string $name = '')
    {
        $txt = '<form ';
        if (!empty($id)) {
            $txt .= ' id="' . $id . '" ';
        }

        if (!empty($name)) {
            $txt .= ' name="' . $name . '" ';
        }

        $txt .= 'method="' . $method . '" action="' . $action . '" data-mgformvalidation>';

        return $txt;
    }

    public static function dataError(string $classname, string $field)
    {
        return sprintf('<div class="%s" data-error="%s"></div>', $classname, $field);
    }

    public static function dataSuccess(string $classname): string
    {
        return sprintf('<div class="%s" data-success></div>', $classname);
    }

    public static function endForm()
    {
        return '</form>';
    }

    public function rules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            $value = trim((string) ($this->data[$field] ?? ''));
            $ruleList = explode('|', $rules);

            foreach ($ruleList as $rule) {
                $this->applyRule($field, $value, $rule);
                if (isset($this->error[$field])) {
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, string $value, string $rule): void
    {
        [$ruleName, $param] = array_pad(explode(':', $rule), 2, null);

        match ($ruleName) {
            'required' => $this->required($field, $value),
            'email' => $this->email($field, $value),
            'min' => $this->min($field, $value, (int) $param),
            'max' => $this->max($field, $value, (int) $param),
            default => null
        };
    }

    private function required(string $field, string $value): void
    {
        if ($value === '') {
            $this->errors[$field] = 'O campo ' . $field . ' é obrigatório.';
        }
    }

    public function email(string $field, string $value): void
    {
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'O campo ' . $field . ' deve ser um e-mail válido.';
        }
    }

    public function min(string $field, string $value, int $min): void
    {
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->errors[$field] = 'O campo ' . $field . ' deve ter no mínimo ' . $min . ' caracteres.';
        }
    }

    public function max(string $field, string $value, int $max): void
    {
        if ($value !== '' && mb_strlen($value) > $max) {
            $this->errors[$field] = 'O campo ' . $field . ' deve ter no máximo ' . $max . ' caracteres';
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function check(string $field): void
    {
        header('Content-Type: application/json;charset=utf-8');
        $rules = [$field => $this->rules[$field] ?? ''];

        $validator = new self($this->data);
        $validator->rules($rules);

        if (!$validator->validate()) {
            echo json_encode([
                'fields' => $field,
                'error' => $validator->errors()[$field] ?? null
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            'field' => $field,
            'error' => null
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function json(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (isset($this->data['_mgformvalidation_field'])) {
            $this->check($this->data['_mgformvalidation_field']);
        }

        if (!$this->validate()) {
            echo json_encode(['errors' => $this->errors(), JSON_UNESCAPED_UNICODE]);
            exit;
        }
    }

    public function success($function)
    {
        $function();
        exit;
    }

    public static function scripts()
    {
        return <<<HTML
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-mgformvalidation]').forEach(function (form) {
                const timers = {};
                async function validateField(field) {
                    const errorEl = form.querySelector('[data-error="' + field.name + '"]');
                    if (!errorEl) return;

                    const data = new FormData();
                    data.append('_mgformvalidation_field', field.name);
                    data.append(field.name, field.value);

                    try {
                        const response = await fetch(form.action || window.location.href, {
                            method: form.method,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(data)
                        });

                        const json = await response.json();
                        errorEl.innerText = json.error || '';
                    } catch (e) {
                        errorEl.innerText = '';
                    }
                }

                form.querySelectorAll('input[name], textarea[name], select[name]').forEach(function (field) {
                    const mode = field.getAttribute('mgformvalidation-live') || 'input';

                    field.addEventListener(mode, function () {
                        clearTimeout(timers[field.name])

                        timers[field.name] = setTimeout(function () {
                            validateField(field);
                        }, 250);
                    });

                    field.addEventListener('blur', function () {
                        clearTimeout(timers[field.name]);
                        validateField(field);
                    });
                });

                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    form.querySelectorAll('[data-error]').forEach(function (el) {
                        el.innerText = '';
                    });

                    const success = form.querySelector('[data-success]');
                    if (success) success.innerText = '';
                    
                    try {
                        const response = await fetch(form.action || window.location.href, {
                            method: form.method,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams(new FormData(form))
                        });

                        const json = await response.json();

                        if (json.errors) {
                            Object.keys(json.errors).forEach(function (field) {
                                const el = form.querySelector('[data-error="' + field + '"]');
                                if (el) el.innerText = json.errors[field];
                            });
                            return;
                        }

                        if (json.success && success) {
                            success.innerText = json.success;
                            form.reset();

                            form.querySelectorAll('[data-error]').forEach(function (el) {
                                el.innerText = '';
                            });
                        }
                    } catch (e) {
                        if (success) {
                            success.innerText = 'Erro ao processar requisição.';
                        }
                    }
                });
            });
        });
        </script>
        HTML;
    }
}
