<?php

namespace App\Services;

use App\DAO\ProductDAO;
use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
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

            return Http::getJsonReponseSuccess($response, ['teste'], $this->lang->insertProductSuccess(), Http::CREATED);
    
        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}