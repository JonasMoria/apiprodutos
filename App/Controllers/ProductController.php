<?php

namespace App\Controllers;

use App\Services\ProductService;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductController {
    private ProductService $service;

    public function __construct() {
        $this->service = new ProductService();
    }

    public function putProduct(Request $request, Response $response, array $args) : Response {
        return $this->service->putProduct($request, $response, $args);
    }
}