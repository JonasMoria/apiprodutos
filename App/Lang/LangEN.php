<?php

namespace App\Lang;

use App\Lang\LangInterface;

class LangEN implements LangInterface {
    public function serverError() {
        return 'The request could not be fulfilled at this time, please wait a few moments.';
    }
    public function success() {
        return 'Success';
    }
}