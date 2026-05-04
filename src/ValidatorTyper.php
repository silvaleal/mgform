<?php

namespace MGForm;

trait ValidatorTyper
{
    private function required(string $field, string $value): void
    {
        if ($value === '') {
            $this->errors[$field] = 'O campo é obrigatório.';
        }
    }

    private function email(string $field, string $value): void
    {
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'O campo deve ser um e-mail válido.';
        }
    }
    private function min(string $field, string $value, int $min): void
    {
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->errors[$field] = 'O campo deve ter no mínimo ' . $min . ' caracteres.';
        }
    }

    private function max(string $field, string $value, int $max): void
    {
        if ($value !== '' && mb_strlen($value) > $max) {
            $this->errors[$field] = 'O campo deve ter no máximo ' . $max . ' caracteres.';
        }
    }
}