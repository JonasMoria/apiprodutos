<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

final class Auth {
    private static function getJwtSecretKey() {
        return $_ENV['JWT_SECRET_KEY'];
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
}