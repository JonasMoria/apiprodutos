<?php

namespace App\Models;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Helpers\Validator;
use App\Lang\Lang;
use App\Lang\LangInterface;

final class ProductModel {
    private int $id;
    private int $storeId;
    private int $views;
    private string $namePT;
    private string $nameEN;
    private string $nameES;
    private string $sku;
    private string $pathImage;
    private int $status;
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function setId(int $productId) {
        if (!Validator::validateId($productId)) {
            throw new InvalidInputException($this->lang->noProductId(), Http::BAD_REQUEST);
        }

        $this->id = $productId;
    }

    public function getId() : int {
        return $this->id;
    }

    public function setStoreId(int $storeId) {
        if (!Validator::validateId($storeId)) {
            throw new InvalidInputException($this->lang->invalidId(), Http::BAD_REQUEST);
        }

        $this->storeId = $storeId;;
    }

    public function getStoreId() : int {
        return $this->storeId;
    }

    public function setViews(int $views) {
        if ($views < 0) {
            throw new InvalidInputException($this->lang->noCountViews(), Http::BAD_REQUEST);
        }
    }

    public function getViews() : int {
        return $this->views;
    }

    public function setNamePortuguese(string $name) {
        if (!Validator::validateName($name)) {
            throw new InvalidInputException($this->lang->namePtNotDefined(), Http::BAD_REQUEST);
        }

        $this->namePT = $name;
    }

    public function getNamePortuguese() : string {
        return $this->namePT;
    }

    public function setNameEnglish(string $name) {
        if (!Validator::validateName($name)) {
            throw new InvalidInputException($this->lang->nameEnNotDefined(), Http::BAD_REQUEST);
        }

        $this->nameEN = $name;
    }

    public function getNameEnglish() : string {
        return $this->nameEN;
    }

    public function setNameSpanish(string $name) {
        if (!Validator::validateName($name)) {
            throw new InvalidInputException($this->lang->nameEsNotDefined(), Http::BAD_REQUEST);
        }

        $this->nameES = $name;
    }

    public function getNameSpanish() : string {
        return $this->nameES;
    }

    public function setProductSKU(string $sku) {
        if (!Validator::validateName($sku)) {
            throw new InvalidInputException($this->lang->invalidSku(), Http::BAD_REQUEST);
        }

        $this->sku = $sku;
    }

    public function getProductSKU() : string {
        return $this->sku;
    }

    public function setProductImage(string $pathImage) {
        $this->$pathImage = $pathImage;
    }

    public function getProductImage() : string {
        return $this->pathImage;
    }

    public function setProductStatus(int $status) {
        if (!Validator::validateDatabaseStatus($status)) {
            throw new InvalidInputException($this->lang->invalidProductStatus(), Http::BAD_REQUEST);
        }
    }

    public function getProductStatus() : int {
        return $this->status;
    }
}