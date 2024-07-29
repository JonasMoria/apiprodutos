<?php

namespace App\DAO;

use App\Database\Connection;
use App\Models\ProductModel;
use App\Traits\DatabaseFlags;

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
}