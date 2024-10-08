<?php

namespace App\Controllers;

use App\Services\ApiService;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApiController {

    private ApiService $service;

    public function __construct() {
        $this->service = new ApiService();
    }

    public function testConnection(Request $request, Response $response, array $args) : Response {
        return $this->service->ping($request, $response, $args);
    }

    public function register(Request $request, Response $response, array $args) : Response {
        return $this->service->registerUser($request, $response, $args);
    }

    public function login(Request $request, Response $response, array $args) : Response {
        return $this->service->loginUser($request, $response, $args);
    }

}