<?php

namespace App\Controllers;

use App\Services\StoreService;
use Slim\Http\Request;
use Slim\Http\Response;

class StoreController {
    private StoreService $service;

    public function __construct() {
        $this->service = new StoreService();
    }

    public function putStore(Request $request, Response $response, array $args) : Response {
        return $this->service->createStore($request, $response, $args);
    }

    public function putLogoImage(Request $request, Response $response, array $args) : Response {
        return $this->service->putStoreLogo($request, $response, $args);
    }
}