<?php

namespace App\Services;

use App\DAO\StoreDAO;
use App\Exceptions\InvalidInputException;
use App\Helpers\Auth;
use App\Helpers\Http;
use App\Helpers\Validator;
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

            if ($storeDAO->getStoreByEmail($store->getEmail())) {
                throw new InvalidInputException($this->lang->storeAlreadyExists(), Http::BAD_REQUEST);
            }

            $storeDAO->insertStore($store);

           return Http::getJsonReponseSuccess($response, [], $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function loginUser(Request $request, Response $response, array $args) : Response {
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
            $storeData = $storeDAO->getStoreAccess($store);

            $this->checkUserAccount($storeData);
            $tokenAccess = Auth::makeJwtToken($storeData[0]);

            return Http::getJsonReponseSuccess($response, ['access_token' => $tokenAccess], $this->lang->loginSuccess(), Http::CREATED);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    private function checkUserAccount($account) {
        if (!$account) {
            throw new InvalidInputException($this->lang->accountNotFound(), Http::BAD_REQUEST);
        }
        if (!Validator::validateActiveAccount($account[0])) {
            throw new InvalidInputException($this->lang->inactiveAccount(), Http::UNAUTHORIZED);
        }
    }
}