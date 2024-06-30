<?php

namespace App\Lang;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;

class Lang {
    const PT = 'pt';
    const US = 'us';
    const ES = 'es';

    public static function getSuccessLabel($lang) {
        switch ($lang) {
            case self::PT:
                return 'Sucesso';
            case self::US;
                return 'Success';
            case self::ES:
                return 'Éxito';
            default:
                throw new InvalidInputException('Language is not allowed or defined.', Http::BAD_REQUEST);
            break;
        }
    }
}