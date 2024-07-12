<?php

namespace App\Lang;

interface LangInterface {

    public function serverError();
    public function success();
    public function invalidId();
    public function invalidStoreEmail();
    public function invalidPassword();
    public function notParamsDetected();
}