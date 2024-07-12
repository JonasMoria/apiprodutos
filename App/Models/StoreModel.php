<?php

namespace App\Models;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Helpers\Validator;
use App\Lang\Lang;
use App\Lang\LangInterface;

class StoreModel {
    private int $id;
    private string $email;
    private string $password;
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function setId(int $id) {
        if (!Validator::validateId($id)) {
            throw new InvalidInputException($this->lang->invalidId(), Http::BAD_REQUEST);
        }

        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setEmail(string $email) {
        if (!Validator::validateEmail($email)) {
            throw new InvalidInputException($this->lang->invalidStoreEmail(), Http::BAD_REQUEST);
        }

        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setPassword(string $password) {
        if (!Validator::validatePassword($password)) {
            throw new InvalidInputException($this->lang->invalidPassword(), Http::BAD_REQUEST);
        }

        $this->password = $password;
    }

    public function getPassword(): string {
        return $this->password;
    }
} 