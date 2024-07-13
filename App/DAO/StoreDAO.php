<?php

namespace App\DAO;

use App\Database\Connection;
use App\Helpers\Utils;
use App\Models\StoreModel;
use App\Traits\DatabaseFlags;
use PDO;

final class StoreDAO extends Connection {
    //Traits
    use DatabaseFlags;
    // Vars
    private $database;
    private $table = 'stores';

    public function __construct() {
        $this->database = self::getPDOConnection();
    }

    public function insertStore(StoreModel $store) {
        $query = "
            INSERT INTO " . $this->table . "
                (`store_email`, `store_password`, `store_status`)
            VALUES
                (?, ?, ?)
            LIMIT 1
        ";

        $this->database
            ->prepare($query)
            ->execute([
                $store->getEmail(),
                Utils::convertToSha512($store->getPassword()),
                self::FLAG_ACTIVE
            ]);
    }

    public function getStoreAccess(StoreModel $store) {
        $query = "
            SELECT
                S.store_id,
                S.store_email,
                S.store_status
            FROM
                " . $this->table . " S
            WHERE
                S.store_email = ?
                AND S.store_password = ?
        ";

        $request = $this->database->prepare($query);
        $request->execute([
            $store->getEmail(),
            Utils::convertToSha512($store->getPassword()),
        ]);

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
}