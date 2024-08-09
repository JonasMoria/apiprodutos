<?php

namespace App\Helpers;

use DateTime;

class ImageManager {
    private $urlRepository;

    public function __construct(string $urlRepository = '') {
        if (!empty($urlRepository)) {
            $this->urlRepository = $urlRepository;
        } else {
            $this->urlRepository = $_SERVER['DOCUMENT_ROOT'] . '/'. $_ENV['PROJECT_NAME'] . '/Repository';
        }
    }

    public function validateBase64Image(string $base64Image) {
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64Image)) {
            return false;  
        } 

        $decoded = base64_decode($base64Image, true);
        if(false === $decoded) {
            return false;
        }

        if(base64_encode($decoded) != $base64Image) {
            return false;
        } 

        return true;
    }

    public function isFolderStoreCreated(int $storeID) {
        clearstatcache();
        $folderPath = $this->makeStoreFolderPath($storeID);
        if (is_dir($folderPath)) {
            return true;
        }

        return false;
    }

    public function createFolderStore(int $storeID) {
        $folderPath = $this->makeStoreFolderPath($storeID);
        if (!mkdir($folderPath, 0777, true)) {
            return false;
        }

        return true;
    }

    public function saveStoreLogo(int $storeID, string $base64Image) {
        $image = base64_decode($base64Image);
        $repository = $this->makeStoreFolderPath($storeID); 
        $imagePath =  $this->createPngImagePath($storeID);

        $pathContent = $repository . '/' . $imagePath;
        if (file_put_contents($pathContent, $image)) {
            return $imagePath;
        }

        return '';
    }

    public function saveProductImage(int $storeID, int $productId, string $base64Image) {
        $image = base64_decode($base64Image);
        $repository = $this->makeStoreFolderPath($storeID);
        $productPath = $this->createProductPngImagePath($storeID, $productId);

        $pathContent = $repository . '/' . $productPath;
        if (file_put_contents($pathContent, $image)) {
            return $productPath;
        }

        return '';
    }

    public function makeStoreFolderPath(int $storeID) {
        return $this->urlRepository . '/' . $storeID;
    }

    public function createPngImagePath(int $storeID) {
        $timestampImage = (new DateTime())->getTimestamp();
        return  $storeID . '_' . $timestampImage . '.png';
    }

    public function createProductPngImagePath(int $storeID, int $productId) {
        $timestampImage = (new DateTime())->getTimestamp();
        return  'prod_' . $storeID . '_' . $productId . '_' . $timestampImage . '.png';
    }
}