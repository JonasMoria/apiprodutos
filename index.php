<?php

use Dotenv\Dotenv;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");
header("Content-Language: en, pt, es");

date_default_timezone_set('America/Sao_Paulo');

require_once 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable('config');
$dotenv->load();

require_once 'routes/routes.php';