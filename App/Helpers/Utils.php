<?php

namespace App\Helpers;

class Utils {
    public static function convertToSha512(string $string) {
        return hash('sha512', $string);
    }

    public static function removeDoubleSpaces(string $string) {
        return preg_replace('/( )+/', ' ', $string);
    }

    public static function filterNumbersOnly(string $string) {
        return preg_replace('/[^0-9]/', '', $string);
    }
}
