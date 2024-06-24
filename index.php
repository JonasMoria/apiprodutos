<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

date_default_timezone_set('America/Sao_Paulo');

require_once 'vendor/autoload.php';
require_once 'routes/routes.php';