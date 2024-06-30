<?php

namespace App\Helpers;

use App\Lang\Lang;
use Slim\Http\Response;

class Http {
    const OK = 200;
    const CREATED = 201;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;

    const SERVER_ERROR = 500;

    public static function getJsonReponseSuccess(Response $response, array $payload = [], string $message = '', int $httpCode = 0) : Response {
        return $response->withJson([
            'message' => $message,
            'status' => $httpCode,
            'data' => $payload,
        ], $httpCode);
    }

    public static function getJsonReponseError(Response $response, string $message, int $httpCode) {
        return $response->withJson([
            'message' => $message,
            'status' => $httpCode,
        ], $httpCode);
    }

    public static function getJsonResponseErrorServer(Response $response, $error) {
        $lang = Lang::getLang();

        return $response->withJson([
            'message' => $lang->serverError(),
            'status' => self::SERVER_ERROR,
        ], self::SERVER_ERROR);
    }
}