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

    public function updateStore(array $storeFields) {
        $queryBuild = $this->convertObjectToSql($storeFields);

        $query = "
            UPDATE " . $this->table . "
            SET
            " . $queryBuild['sets'] . "
            WHERE
                store_info_store_id = ?
            LIMIT 1
        ";

        $this->database
            ->prepare($query)
            ->execute($queryBuild['params']);
    }

    private function convertObjectToSql(array $storeFields) {
        $arrayPrepare = [];
        $sets = [];

        if (isset($storeFields['name'])) {
            array_push($sets,'store_name = ?');
            array_push($arrayPrepare, $storeFields['name']);
        }

        if (isset($storeFields['email'])) {
            array_push($sets,'store_email = ?');
            array_push($arrayPrepare, $storeFields['email']);
        }

        if (isset($storeFields['cnpj'])) {
            array_push($sets,'store_cnpj = ?');
            array_push($arrayPrepare, $storeFields['cnpj']);
        }

        if (isset($storeFields['lat']) && isset($storeFields['lon'])) {
            array_push($sets,'store_coordinate = POINT(?, ?)');
            array_push($arrayPrepare, $storeFields['lat']);
            array_push($arrayPrepare, $storeFields['lon']);
        }

        array_push($arrayPrepare, $storeFields['store_id']);

        return [
            'sets' => implode(',', $sets),
            'params' => $arrayPrepare
        ];
    }
}