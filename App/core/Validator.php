<?php

namespace App\core;

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $ruleset) {
            foreach ($ruleset as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }
        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule) {
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "Le champ $field est requis.";
        }

        if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "Le champ $field doit être un email valide.";
        }

        if (str_starts_with($rule, 'min:')) {
            $min = (int)explode(':', $rule)[1];
            if (strlen($value) < $min) {
                $this->errors[$field][] = "Le champ $field doit contenir au moins $min caractères.";
            }
        }

        if (str_starts_with($rule, 'max:')) {
            $max = (int)explode(':', $rule)[1];
            if (strlen($value) > $max) {
                $this->errors[$field][] = "Le champ $field ne peut pas dépasser $max caractères.";
            }
        }

        if ($rule === 'password' && !preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $value)) {
            $this->errors[$field][] = "Le mot de passe doit contenir au moins 6 caractères, une lettre et un chiffre.";
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
