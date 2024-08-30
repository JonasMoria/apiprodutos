<?php

namespace App\Services;

use App\DAO\ProductDAO;
use App\Exceptions\InvalidInputException;
use App\Exceptions\UploadException;
use App\Helpers\Http;
use App\Helpers\ImageManager;
use App\Helpers\Utils;
use App\Helpers\Validator;
use App\Lang\Lang;
use App\Lang\LangInterface;
use App\Models\ProductModel;
use Dotenv\Exception\InvalidFileException;
use Slim\Http\Request;
use Slim\Http\Response;

final class ProductService {
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function putProduct(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');

            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            if (!is_array($params) || !Validator::arrayKeysExists(['name_pt', 'name_en', 'name_es', 'sku'], $params)) {
                throw new InvalidInputException($this->lang->invalidRequestParams(), Http::BAD_REQUEST);
            }

            $product = new ProductModel();
            $product->setStoreId($loginParams['store_id']);
            $product->setNamePortuguese($params['name_pt']);
            $product->setNameEnglish($params['name_en']);
            $product->setNameSpanish($params['name_es']);
            $product->setProductSKU($params['sku']);

            $dao = new ProductDAO();
            $dao->insertProduct($product);

            return Http::getJsonReponseSuccess($response, [], $this->lang->insertProductSuccess(), Http::CREATED);
    
        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function updateProduct(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');
            
            $productId = Utils::filterNumbersOnly($args['id']);
            if (empty($productId)) {
                throw new InvalidInputException($this->lang->unidentifiedId(), Http::BAD_REQUEST);
            }

            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $fieldsToUpdate = [];
            if (isset($params['name_pt'])) {
                $name = Utils::removeDoubleSpaces($params['name_pt']);
                if (!Validator::validateName($name)) {
                    throw new InvalidInputException($this->lang->namePtNotDefined(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['name_pt'] = $name;
            }

            if (isset($params['name_es'])) {
                $name = Utils::removeDoubleSpaces($params['name_es']);
                if (!Validator::validateName($name)) {
                    throw new InvalidInputException($this->lang->nameEsNotDefined(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['name_es'] = $name;
            }

            if (isset($params['name_en'])) {
                $name = Utils::removeDoubleSpaces($params['name_en']);
                if (!Validator::validateName($name)) {
                    throw new InvalidInputException($this->lang->nameEnNotDefined(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['name_en'] = $name;
            }

            if (isset($params['sku'])) {
                $sku = Utils::removeDoubleSpaces($params['sku']);
                if (!Validator::validateName($sku)) {
                    throw new InvalidInputException($this->lang->insertProductSuccess(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['sku'] = $sku;
            }

            if (empty($fieldsToUpdate)) {
                throw new InvalidInputException($this->lang->noDataToUpdate(), Http::BAD_REQUEST);
            }

            $fieldsToUpdate['store_id'] = $loginParams['store_id'];
            $fieldsToUpdate['product_id'] = $productId;

            $dao = new ProductDAO();
            if (!$dao->updateProduct($fieldsToUpdate)) {
                throw new InvalidInputException($this->lang->updateProductFail(), Http::BAD_REQUEST);
            }

            return Http::getJsonReponseSuccess($response, [], $this->lang->updateProductSuccess(), Http::CREATED);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function deleteProduct(Request $request, Response $response, array $args) : Response {
        try {
            $loginParams = $request->getAttribute('jwt');
            
            $productId = Utils::filterNumbersOnly($args['id']);
            if (empty($productId)) {
                throw new InvalidInputException($this->lang->unidentifiedId(), Http::BAD_REQUEST);
            }

            if (!$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $storeId = (int) $loginParams['store_id'];
            $productId = (int) $productId;

            $dao = new ProductDAO();
            if (!$dao->deleteProduct($storeId, $productId)) {
                throw new InvalidInputException($this->lang->deleteProductFail(), Http::BAD_REQUEST);
            }

            return Http::getJsonReponseSuccess($response, [], $this->lang->deleteProductSuccess(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function putImage(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');
            if (!isset($params['base64Image']) || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }
    
            $productId = Utils::filterNumbersOnly($args['id']);
            if (empty($productId)) {
                throw new InvalidInputException($this->lang->unidentifiedId(), Http::BAD_REQUEST);
            }

            $imageManager = new ImageManager();

            $dao = new ProductDAO();
            $productImagePath = $dao->getImageProduct($loginParams['store_id'], $productId);
            if ($productImagePath['img']) {
                $repository = $imageManager->makeStoreFolderPath($loginParams['store_id']);
                $imagePath = $repository . '/' . $productImagePath['img'];
                $imageManager->deleteImage($imagePath);
            }
    

            $base64Image = $params['base64Image'];
            if (!$imageManager->validateBase64Image($base64Image)) {
                return Http::getJsonReponseError($response, $this->lang->notBase64valid(), Http::BAD_REQUEST);
            }

            if (!$imageManager->isFolderStoreCreated($loginParams['store_id'])) {
                if (!$imageManager->createFolderStore($loginParams['store_id'])) {
                    throw new UploadException($this->lang->noFolderCreated(), Http::SERVER_ERROR);
                }
            }

            $storeId = (int) $loginParams['store_id'];

            $productPath = $imageManager->saveProductImage($storeId, $productId, $base64Image);
            if (empty($productPath)) {
                throw new InvalidFileException($this->lang->noImageCreated(), Http::SERVER_ERROR);
            }

            $dao->putProductImage($storeId, $productId, $productPath);

            $arrayReturn = [
                'url_image' => $imageManager->makeStoreFolderPath($loginParams['store_id']) . '/' . $productPath
            ];

            return Http::getJsonReponseSuccess($response, $arrayReturn, $this->lang->imageRegistered(), Http::CREATED);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (UploadException $upError) {
            return Http::getJsonReponseError($response, $upError->getMessage(), $upError->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function viewProduct(Request $request, Response $response, array $args) : Response {
        try {
            $loginParams = $request->getAttribute('jwt');

            $productId = Utils::filterNumbersOnly($args['id']);
            if (empty($productId)) {
                throw new InvalidInputException($this->lang->unidentifiedId(), Http::BAD_REQUEST);
            }

            if (!$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $storeId = (int) $loginParams['store_id'];
            $productId = (int) $productId;

            $dao = new ProductDAO();
            $product = $dao->getProduct($storeId, $productId);

            if (is_array($product) && isset($product['product_path_image'])) {
                $imageManager = new ImageManager();

                if (!empty($product['product_path_image'])) {
                    $repository = $imageManager->makeStoreFolderPath($loginParams['store_id']);
                    $imagePath = $repository . '/' . $product['product_path_image'];
                    $product['product_path_image'] = $imagePath;
                } else {
                    $product['product_path_image'] = $imageManager->makeUrlNoImage();
                }
            }

            return Http::getJsonReponseSuccess($response, $product, $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function searchProducts(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            if (!$params) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $searchFilters = [];
            if (isset($params['name_pt'])) {
                $searchFilters['name_pt'] = $params['name_pt'];
            }

            if (isset($params['name_en'])) {
                $searchFilters['name_en'] = $params['name_en'];
            }

            if (isset($params['name_es'])) {
                $searchFilters['name_es'] = $params['name_es'];
            }

            $dao = new ProductDAO();
            $products = $dao->findProducts($searchFilters);

            return Http::getJsonReponseSuccess($response, $products, $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}