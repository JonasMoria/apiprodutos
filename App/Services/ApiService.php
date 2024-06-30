<?php

namespace App\Services;

use App\Helpers\Http;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApiService {

    public function ping(Request $request, Response $response, array $args) {
        try {
            $apiStatus['pong'] = true;

            return Http::getJsonReponseSuccess($response, $apiStatus, 'Sucesso', Http::OK);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}