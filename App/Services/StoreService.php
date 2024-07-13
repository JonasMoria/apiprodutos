<?php

namespace App\Services;

use App\Exceptions\InvalidInputException;
use App\Helpers\Http;
use App\Lang\Lang;
use App\Lang\LangInterface;
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
            if (!$params) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            return Http::getJsonReponseSuccess($response, [], 'teste', Http::CREATED);
        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}