<?php

namespace App\Helpers;

use App\Exceptions\InvalidInputException;
use App\Traits\DatabaseFlags;

class Validator {
    use DatabaseFlags;

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

    public static function validateActiveAccount($storeAccount) {
        if ($storeAccount['store_status'] == self::FLAG_INACTIVE) {
            return false;
        }

        return true;
    }
}