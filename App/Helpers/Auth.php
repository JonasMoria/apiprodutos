<?php

namespace App\Helpers;

use App\Lang\Lang;
use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Middleware\JwtAuthentication;

final class Auth {
    private static function getJwtSecretKey() {
        return $_ENV['JWT_SECRET_KEY'];
    }

    private static function getLang() {
        return Lang::getLang();
    }

    /**
     * Create a JWT token to login access
     * @param array $store Store informations
     * @return string JWT token
     */
    public static function makeJwtToken(array $store) {
        $payload = [
            'store_id' => $store['store_id'],
            'store_email' => $store['store_email'],
            'expired_at' => (new \DateTime())->modify('+1 hours')->format('Y-m-d H:i:s')
        ];

        return JWT::encode($payload, self::getJwtSecretKey(), 'HS256');
    }

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
}