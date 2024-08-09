<?php

use App\Controllers\ApiController;
use App\Controllers\ProductController;
use App\Controllers\StoreController;
use App\Middlewares\AuthMiddleware;

$api = new \Slim\App();

// Public routes
$api->group('/api', function() use ($api) {
    $api->get('/ping', ApiController::class . ':testConnection');
    $api->post('/register', ApiController::class . ':register');
    $api->post('/login', ApiController::class . ':login');
});

// Store Routes
$api->group('/api/store', function() use ($api) {
    $api->post('/register', StoreController::class . ':putStore');
    $api->post('/update', StoreController::class . ':updateStore');
    $api->post('/update/logo', StoreController::class . ':putLogoImage');
})
->add(AuthMiddleware::class . ':validateJwtToken')
->add(AuthMiddleware::jwtAuth());

// Product Routes
$api->group('/api/store/product', function() use ($api) {
    $api->post('/register', ProductController::class . ':putProduct');
    $api->post('/update/{id}', ProductController::class . ':updateProduct');
    $api->post('/update/logo/{id}', ProductController::class . ':putImage');
    $api->post('/delete/{id}', ProductController::class . ':deleteProduct');
})
->add(AuthMiddleware::class . ':validateJwtToken')
->add(AuthMiddleware::jwtAuth());

$api->run();
