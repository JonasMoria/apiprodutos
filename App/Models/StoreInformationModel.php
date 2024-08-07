<?php

namespace App\Models;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Helpers\Validator;
use App\Lang\Lang;
use App\Lang\LangInterface;

class StoreInformationModel {
    private int $id;
    private int $infoId;
	private string $name;
    private string $email;
    private string $cnpj;
    private string $base64Image;
    private string $lat;
    private string $lon;
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function setStoreRegisterId(int $id) {
        if (!Validator::validateId($id)) {
            throw new InvalidInputException($this->lang->invalidId(), Http::BAD_REQUEST);
        }

        $this->id = $id;
    }

    public function getStoreRegisterId() {
        return $this->id;
    }

    public function setStoreInfoId(int $infoId) {
        if (!Validator::validateId($infoId)) {
            throw new InvalidInputException($this->lang->invalidId(), Http::BAD_REQUEST);
        }

        $this->infoId = $infoId;
    }

    public function getStoreInfoId() {
        return $this->infoId;
    }

    public function setStoreName(string $name) {
        if (!Validator::validateName($name)) {
            throw new InvalidInputException($this->lang->invalidStoreName(), Http::BAD_REQUEST);
        }
        $this->name = $name;
    }

    public function getStoreName() {
        return $this->name;
    }

    public function setStoreEmail(string $email) {
        if (!Validator::validateEmail($email)) {
            throw new InvalidInputException($this->lang->invalidStoreEmail(), Http::BAD_REQUEST);
        }

        $this->email = $email;
    }

    public function getStoreEmail() {
        return $this->email;
    }

    public function setStoreCnpj(string $cnpj) {
        if (!Validator::validateCNPJ($cnpj)) {
            throw new InvalidInputException($this->lang->invalidCNPJ(), Http::BAD_REQUEST);
        }
        $this->cnpj = $cnpj;
    }

    public function getStoreCnpj() {
        return $this->cnpj;
    }

    public function setStoreImage(string $base64Image) {
        $this->base64Image = $base64Image;
    }

    public function getStoreImage() {
        return $this->base64Image;
    }

    public function setStoreLatitude(string $lat) {
        $this->lat = $lat;
    }

    public function getStoreLatitude() {
        return $this->lat;
    }

    public function setStoreLongitude(string $lon) {
        $this->lon = $lon;
    }

    public function getStoreLongitude() {
        return $this->lon;
    }
}