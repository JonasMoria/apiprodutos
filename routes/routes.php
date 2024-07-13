<?php

use App\Controllers\ApiController;
use App\Controllers\StoreController;
use App\Helpers\Auth;
use Slim\Http\Request;

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
->add(Auth::class . ':validateJwtToken')
->add(Auth::jwtAuth());


$api->run();