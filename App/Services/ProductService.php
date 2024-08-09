<?php

namespace App\Services;

use App\DAO\ProductDAO;
use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Helpers\Utils;
use App\Helpers\Validator;
use App\Lang\Lang;
use App\Lang\LangInterface;
use App\Models\ProductModel;
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
}