<?php

use App\Controllers\ApiController;
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
})
->add(AuthMiddleware::class . ':validateJwtToken')
->add(AuthMiddleware::jwtAuth());

$api->run();
