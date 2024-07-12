<?php

namespace App\Helpers;

class Utils {
    public static function convertToSha512(string $string) {
        return hash('sha512', $string);
    }
}