<?php

namespace App\Services;

use App\DAO\StoreDAO;
use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Lang\Lang;
use App\Lang\LangInterface;
use App\Models\StoreModel;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApiService {
    private LangInterface $lang;

    public function __construct() {
        $this->lang = Lang::getLang();
    }

    public function ping(Request $request, Response $response, array $args): Response {
        try {
            $apiStatus['pong'] = true;
            return Http::getJsonReponseSuccess($response, $apiStatus, $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function registerUser(Request $request, Response $response, array $args): Response {
        try {
            $params = $request->getParsedBody();
            if (!$params) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $store = new StoreModel();
            $store->setId(0);
            $store->setEmail($params['email']);
            $store->setPassword($params['password']);

            $storeDAO = new StoreDAO();
            $storeDAO->insertStore($store);

           return Http::getJsonReponseSuccess($response, [], $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}