<?php

namespace App\DAO;

use App\Database\Connection;
use App\Helpers\Utils;
use App\Models\StoreInformationModel;
use PDO;

final class StoreInfoDAO extends Connection {
    private $database;
    private $table = 'stores_informations';

    public function __construct() {
        $this->database = self::getPDOConnection();
    }

    public function getStoreInformationByStoreId(int $storeId) {
        $query = "
            SELECT
                SI.*
            FROM
                " . $this->table . " SI
            WHERE
                SI.store_info_store_id = ?
        ";

        $request = $this->database->prepare($query);
        $request->execute([
            $storeId
        ]);

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertInfoStore(StoreInformationModel $store) {
        $query = "
            INSERT INTO " . $this->table . "
                (`store_info_store_id`, `store_name`, `store_email`, `store_cnpj`, `store_coordinate`)
            VALUES
                (?, ?, ?, ?, POINT(?, ?))
            LIMIT 1
        ";

        $this->database
            ->prepare($query)
            ->execute([
                $store->getStoreRegisterId(),
                $store->getStoreName(),
                $store->getStoreEmail(),
                $store->getStoreCnpj(),
                $store->getStoreLatitude(),
                $store->getStoreLongitude()
            ]);
    }

    public function putStoreLogo(int $storeId, string $logoPath) {
        $query = "
            UPDATE " . $this->table . "
            SET
                store_path_logo = ?
            WHERE
                store_info_store_id = ?
            LIMIT 1
        ";
    
        $this->database
            ->prepare($query)
            ->execute([
                $logoPath,
                $storeId
            ]);
    }

    public function getStorePathLogo(int $storeId) {
        $query = "
            SELECT
                SI.store_path_logo
            FROM
                " . $this->table . " SI
            WHERE
                SI.store_info_store_id = ?
            LIMIT 1
        ";

        $request = $this->database->prepare($query);
        $request->execute([
            $storeId
        ]);

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
}