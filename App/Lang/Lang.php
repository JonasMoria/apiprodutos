<?php

namespace App\Lang;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;

class Lang {
    const PT_LANGS = ['pt'];
    const ES_LANGS = ['es'];
    const EN_LANGS = ['en'];

    public static function getLang() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en';
        }

        $lang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        if (in_array($lang, self::PT_LANGS)) {
            return new LangPT;
        }
        if (in_array($lang, self::ES_LANGS)) {
            return new LangES;
        }
        if (in_array($lang, self::EN_LANGS)) {
            return new LangEN;
        }

        return new LangEN;
    }

    public function serverError(LangInterface $lang) {
        return $lang->serverError();
    }

    public function success(LangInterface $lang) {
        return $lang->success();
    }

    public function invalidId(LangInterface $lang) {
            return $lang->invalidId();
    }
}