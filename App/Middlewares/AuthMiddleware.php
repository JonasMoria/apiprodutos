<?php

namespace App\Middlewares;

use App\Helpers\Http;
use App\Lang\Lang;
use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Middleware\JwtAuthentication;

class AuthMiddleware {
    public static function jwtAuth() : JwtAuthentication {
        return new JwtAuthentication([
            'secret' => self::getJwtSecretKey(),
            'attribute' => 'jwt'
        ]);
    }

    public static function validateJwtToken(Request $request, Response $response, $next) : Response {
        $tokenAccess = $request->getAttribute('jwt');
        $expireDate = new \DateTime($tokenAccess['expired_at']);
        $now = new \DateTime();

        $lang = self::getLang();
        if ($expireDate < $now) {
            return Http::getJsonReponseError($response, $lang->tokenExpired(), Http::UNAUTHORIZED);
        }

        $response = $next($request, $response);
        return $response;
    }

    private static function getLang() {
        return Lang::getLang();
    }

    private static function getJwtSecretKey() {
        return $_ENV['JWT_SECRET_KEY'];
    }
}