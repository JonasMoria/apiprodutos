<?php

namespace App\Lang;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;

class Lang {
    const PT_LANGS = ['pt'];
    const ES_LANGS = ['es'];
    const EN_LANGS = ['en'];

    public static function getLang() {
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

        throw new InvalidInputException('Language is not defined or alowed. Check your header parameters.', Http::BAD_REQUEST);
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