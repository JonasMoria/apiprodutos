<?php

namespace App\Services;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Lang\Lang;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApiService {

    public function ping(Request $request, Response $response, array $args) {
        try {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $apiStatus['pong'] = true;
            return Http::getJsonReponseSuccess($response, $apiStatus, Lang::getSuccessLabel($lang), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}