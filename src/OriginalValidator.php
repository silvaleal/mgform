<?php
// Copyright (C) 2026 Murilo Gomes Julio
// SPDX-License-Identifier: LGPL-2.1-only
//
// Site: https://mugomes.github.io

namespace MGFormValidation;

use MGForm\ValidatorTyper;

class OriginalValidator
{
    use ValidatorTyper;

    protected array $data = [];
    protected array $rules = [];
    protected array $errors = [];

    protected string $type = 'success';

    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function __construct(string|array $data = self::METHOD_POST)
    {
        if (is_array($data)) {
            $this->data = $data;
        } else {
            $this->data = ($data === self::METHOD_POST) ? $_POST : $_GET;
        }
    }

    protected function apply()
    {
        $this->errors = [];

        foreach ($this->rules as $field => $rules) {
            $value = trim((string) ($this->data[$field] ?? ''));
            $ruleList = explode('|', $rules);

            foreach ($ruleList as $rule) {
                $this->applyRule($field, $value, $rule);

                if (isset($this->errors[$field])) {
                    $this->type = 'error';
                }
            }
        }

        return empty($this->errors);
    }

    private function applyRule(string $field, string $value, string $rule): void
    {
        $parts = explode(':', $rule);
        $ruleName = $parts[0] ?? null;

        match ($ruleName) {
            'required' => $this->required($field, $value),
            'email'    => $this->email($field, $value),
            'min'      => $this->min($field, $value, (int) ($parts[1] ?? 0)),
            'max'      => $this->max($field, $value, (int) ($parts[1] ?? 0)),
            default    => null
        };
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getData() {
        return $this->data;
    }

    public function getType() {
        return $this->type;
    }
    
    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function success(callable $function): void
    {
        $function();
        exit;
    }
}