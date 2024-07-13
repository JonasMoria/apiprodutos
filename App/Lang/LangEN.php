<?php

namespace App\Lang;

use App\Lang\LangInterface;

class LangEN implements LangInterface {
    public function serverError(): string {
        return 'The request could not be fulfilled at this time, please wait a few moments.';
    }

    public function success(): string {
        return 'Success.';
    }

    public function invalidId(): string {
        return 'Invalid store id.';
    }

    public function invalidStoreEmail(): string {
        return 'Invalid store email.';
    }

    public function invalidPassword(): string {
        return 'Password does not meet the necessary requirements.';
    }

    public function notParamsDetected() {
        return 'Unidentified parameters.';
    }

    public function loginSuccess() {
        return 'Login successful.';
    }

    public function accountNotFound() {
        return 'Incorrect username or password.';
    }

    public function inactiveAccount() {
        return 'Account deactivated. Contact our support for more information.';
    }

    public function tokenExpired() {
        return 'Expired access token. login again.';
    }
}