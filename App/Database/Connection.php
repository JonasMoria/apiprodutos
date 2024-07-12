<?php

namespace App\Database;

use PDO;

class Connection {

    /**
     * Method to connect in database with PDO
     */
    public static function getPDOConnection() {
        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        return new PDO("mysql:host=$host;dbname=$dbName;", $user, $pass);
    }
}