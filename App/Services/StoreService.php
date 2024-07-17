<?php

namespace App\Services;

use App\DAO\StoreInfoDAO;
use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Helpers\Utils;
use App\Lang\Lang;
use App\Lang\LangInterface;
use App\Models\StoreInformationModel;
use Slim\Http\Request;
use Slim\Http\Response;

class StoreService {
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function createStore(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');

            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $storeInfo = new StoreInformationModel();
            $storeInfo->setStoreRegisterId($loginParams['store_id']);
            $storeInfo->setStoreName(Utils::removeDoubleSpaces($params['name']));
            $storeInfo->setStoreEmail($params['email']);
            $storeInfo->setStoreCnpj(Utils::filterNumbersOnly($params['cnpj']));
            $storeInfo->setStoreLatitude($params['lat']);
            $storeInfo->setStoreLongitude($params['lon']);

            $storeInfoDAO = new StoreInfoDAO();
            if ($storeInfoDAO->getStoreInformationByStoreId($storeInfo->getStoreRegisterId())) {
                throw new InvalidInputException($this->lang->storeInformationAlreadyRegistered(), Http::BAD_REQUEST);
            }

            $storeInfoDAO->insertInfoStore($storeInfo);
            return Http::getJsonReponseSuccess($response, [], $this->lang->storeInformationRegistered(), Http::CREATED);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}