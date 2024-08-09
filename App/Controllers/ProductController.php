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

    public function updateProduct(Request $request, Response $response, array $args) : Response {
        return $this->service->updateProduct($request, $response, $args);
    }

    public function deleteProduct(Request $request, Response $response, array $args) : Response {
        return $this->service->deleteProduct($request, $response, $args);
    }

    public function putImage(Request $request, Response $response, array $args) : Response {
        return $this->service->putImage($request, $response, $args);
    }
}