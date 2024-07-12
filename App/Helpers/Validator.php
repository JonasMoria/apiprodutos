<?php

namespace App\Helpers;

class Validator {
    public static function validateId(int $id) {
        if ($id < 0) {
            return false;
        }
        return true;
    }

    public static function validateEmail(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate a password.
     * Requirements:
     * - Has at least one lowercase letter
     * - Has at least one capital letter
     * - Has at least one number
     * - Has 6 or more characters
     */
    public static function validatePassword(string $password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/', $password);
    }
}