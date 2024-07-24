<?php

namespace App\Lang;

interface LangInterface {

    public function serverError();
    public function success();
    public function invalidId();
    public function invalidStoreEmail();
    public function invalidPassword();
    public function notParamsDetected();
    public function loginSuccess();
    public function accountNotFound();
    public function inactiveAccount();
    public function tokenExpired();
    public function invalidCNPJ();
    public function storeAlreadyExists();
    public function storeInformationRegistered();
    public function storeInformationAlreadyRegistered();
    public function notBase64valid();
    public function imageRegistered();
    public function noFolderCreated();
    public function noImageCreated();
    public function logoAlreadyExists();
}