<?php

namespace App\Controllers;

use App\Services\ApiService;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApiController {

    private $service;

    public function __construct() {
        $this->service = new ApiService();
    }

    public function testConnection(Request $request, Response $response, array $args) : Response {
        return $this->service->ping($request, $response, $args);
    }

}