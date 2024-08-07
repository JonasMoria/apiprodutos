<?php

use App\Controllers\ApiController;
use App\Controllers\ProductController;
use App\Controllers\StoreController;
use App\Middlewares\AuthMiddleware;

$api = new \Slim\App();

// public routes
$api->group('/api', function() use ($api) {
    $api->get('/ping', ApiController::class . ':testConnection');
    $api->post('/register', ApiController::class . ':register');
    $api->post('/login', ApiController::class . ':login');
});

// private routes
$api->group('/api/store', function() use ($api) {
    $api->post('/register', StoreController::class . ':putStore');
    $api->post('/update', StoreController::class . ':updateStore');
    $api->post('/update/logo', StoreController::class . ':putLogoImage');
})
->add(AuthMiddleware::class . ':validateJwtToken')
->add(AuthMiddleware::jwtAuth());

$api->group('/api/store/product', function() use ($api) {
    $api->post('/register', ProductController::class . ':putProduct');
})
->add(AuthMiddleware::class . ':validateJwtToken')
->add(AuthMiddleware::jwtAuth());

$api->run();
