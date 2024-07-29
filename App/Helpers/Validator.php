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

    public static function validateCNPJ($cnpj) {
        $cnpj = Utils::filterNumbersOnly($cnpj);
    
        if (strlen($cnpj) != 14) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function validateName(string $name) {
        if (empty(trim($name))) {
            return false;
        }

        if (strlen($name) < 3 || strlen($name) > 256) {
            return false;
        }

        return true;
    }

    public static function validateDatabaseStatus(int $status) {
        if (!in_array($status, [self::FLAG_ACTIVE, self::FLAG_INACTIVE])) {
            return false;
        }

        return true;
    }
}