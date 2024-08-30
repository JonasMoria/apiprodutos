<?php

namespace App\Lang;

interface LangInterface {

    public function serverError();
    public function success();
    public function invalidId();
    public function unidentifiedId();
    public function invalidStoreEmail();
    public function invalidStoreName();
    public function invalidPassword();
    public function notParamsDetected();
    public function invalidRequestParams();
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
    public function noProductId();
    public function noCountViews();
    public function namePtNotDefined();
    public function nameEnNotDefined();
    public function nameEsNotDefined();
    public function invalidSku();
    public function invalidProductStatus();
    public function insertProductSuccess();
    public function noDataToUpdate();
    public function updateProductSuccess();
    public function updateProductFail();
    public function deleteProductSuccess();
    public function deleteProductFail();
}