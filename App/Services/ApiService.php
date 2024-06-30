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
            $lang = Lang::getLang();

            $apiStatus['pong'] = true;
            return Http::getJsonReponseSuccess($response, $apiStatus, $lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}