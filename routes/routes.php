<?php

use App\Controllers\ApiController;

$api = new \Slim\App();

// public routes
$api->group('/api', function() use ($api) {
    $api->get('/ping', ApiController::class . ':testConnection');
});

// private routes


$api->run();