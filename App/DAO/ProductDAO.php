<?php

namespace App\DAO;

use App\Database\Connection;
use App\Models\ProductModel;
use App\Traits\DatabaseFlags;
use PDO;

final class ProductDAO extends Connection{
    //Traits
    use DatabaseFlags;
    // Vars
    private $database;
    private $table = 'stores_products';

    public function __construct() {
        $this->database = self::getPDOConnection();
    }

    public function insertProduct(ProductModel $product) {
        $query = "
            INSERT INTO " . $this->table . "
                (`product_store_id`, `product_name_pt`, `product_name_en`, `product_name_es`, `product_sku`)
            VALUES
                (?, ?, ?, ?, ?)
            LIMIT 1
        ";

        return $this->database
                    ->prepare($query)
                    ->execute([
                        $product->getStoreId(),
                        $product->getNamePortuguese(),
                        $product->getNameEnglish(),
                        $product->getNameSpanish(),
                        $product->getProductSKU()
                    ]);
    }

    public function deleteProduct(int $storeId, int $productId) {
        $query = "
            UPDATE " .  $this->table . "
            SET product_status = " . self::FLAG_INACTIVE . "
            WHERE
                product_id = ?
                AND product_store_id = ?
            LIMIT 1
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute([$productId, $storeId]);

        return $stmt->rowCount();
    }

    public function updateProduct(array $productFields) {
        $queryBuild = $this->convertObjectToSql($productFields);

        $query = "
            UPDATE " . $this->table . "
            SET
                " . $queryBuild['sets'] . "
            WHERE
                product_store_id = ?
                AND product_id = ?
            LIMIT 1
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute($queryBuild['params']);

        return $stmt->rowCount();
    }

    private function convertObjectToSql(array $productFields) {
        $arrayPrepare = [];
        $sets = [];

        if (isset($productFields['name_pt'])) {
            array_push($sets,'product_name_pt = ?');
            array_push($arrayPrepare, $productFields['name_pt']);
        }

        if (isset($productFields['name_es'])) {
            array_push($sets,'product_name_es = ?');
            array_push($arrayPrepare, $productFields['name_es']);
        }

        if (isset($productFields['name_en'])) {
            array_push($sets,'product_name_en = ?');
            array_push($arrayPrepare, $productFields['name_en']);
        }

        if (isset($productFields['sku'])) {
            array_push($sets,'product_sku = ? ');
            array_push($arrayPrepare, $productFields['sku']);
        }

        array_push($arrayPrepare, $productFields['store_id']);
        array_push($arrayPrepare, $productFields['product_id']);

        return [
            'sets' => implode(',', $sets),
            'params' => $arrayPrepare
        ];
    }

    public function putProductImage(int $storeId, int $productId, string $path) {
        $query = "
            UPDATE " . $this->table . "
            SET
                product_path_image = ?
            WHERE
                product_id = ?
                AND product_store_id = ?
            LIMIT 1
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute([$path, $productId, $storeId]);

        return $stmt->rowCount();
    }

    public function getImageProduct(int $storeId, int $productId) {
        $query = "
            SELECT
               P.product_path_image AS img
            FROM
                " . $this->table. " P
            WHERE
                P.product_id = ?
                AND P.product_store_id = ?
            LIMIT 1
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute([$productId, $storeId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProduct(int $storeId, $productId) {
        $query = "
            SELECT
                P.product_id,
                P.product_views,
                P.product_name_pt,
                P.product_name_en,
                P.product_name_es,
                P.product_sku,
                P.product_path_image,
                P.product_status
            FROM
                " . $this->table . " P
            WHERE
                P.product_id = ?
                AND P.product_store_id = ?
                AND P.product_status = ?
            LIMIT 1
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute([$productId, $storeId, self::FLAG_ACTIVE]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) {
            return $result;
        }

        return [];
    }

    public function findProducts(array $filters) : array {
        if (empty($filters)) {
            return [];
        }

        $queryAux = $this->buildWhereFindProducts($filters);

        $query = "
            SELECT
                SI.store_name,
                SI.store_email,
                P.product_id,
                P.product_views,
                P.product_name_pt,
                P.product_name_en,
                P.product_name_es,
                P.product_sku,
                P.product_path_image,
                P.product_status
            FROM
                " . $this->table . " P
            INNER JOIN
                stores_informations SI
                    ON SI.store_info_store_id = P.product_store_id
            WHERE
                " . $queryAux['filters'] . "
            LIMIT 50
        ";

        $stmt = $this->database->prepare($query);
        $stmt->execute($queryAux['binds']);

        $produtcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (is_array($produtcs)) {
            return $produtcs;
        }

        return [];
    }

    private function buildWhereFindProducts(array $filters) {
        $wheres = 'P.product_status = ?';
        $binds = [self::FLAG_ACTIVE];

        if (isset($filters['name_pt'])) {
            $wheres .= ' AND P.product_name_pt LIKE ?';
            array_push($binds, '%' . $filters['name_pt'] . '%');
        }

        if (isset($filters['name_en'])) {
            $wheres .= ' AND P.product_name_en LIKE ?';
            array_push($binds, '%' . $filters['name_en'] . '%');
        }

        if (isset($filters['name_es'])) {
            $wheres .= ' AND P.product_name_es LIKE ?';
            array_push($binds, '%' . $filters['name_es'] . '%');
        }

        return [
            'filters' => $wheres,
            'binds' => $binds
        ];
    }
}