<?php

namespace App\Services;

use App\DAO\StoreInfoDAO;
use App\Exceptions\InvalidInputException;
use App\Exceptions\UploadException;
use App\Helpers\Http;
use App\Helpers\ImageManager;
use App\Helpers\Utils;
use App\Lang\Lang;
use App\Lang\LangInterface;
use App\Models\StoreInformationModel;
use Dotenv\Exception\InvalidFileException;
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

    public function putStoreLogo(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');

            $storeInfoDAO = new StoreInfoDAO();
            if ($storeInfoDAO->getStorePathLogo($loginParams['store_id'])[0]['store_path_logo']) {
                throw new InvalidInputException($this->lang->logoAlreadyExists(), Http::BAD_REQUEST);
            }

            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $base64Image = $params['base64Image'];

            $imageManager = new ImageManager();
            if (!$imageManager->validateBase64Image($base64Image)) {
                return Http::getJsonReponseError($response, $this->lang->notBase64valid(), Http::BAD_REQUEST);
            }

            if (!$imageManager->isFolderStoreCreated($loginParams['store_id'])) {
                if (!$imageManager->createFolderStore($loginParams['store_id'])) {
                    throw new UploadException($this->lang->noFolderCreated(), Http::SERVER_ERROR);
                }
            }

            $logoPath = $imageManager->saveStoreLogo($loginParams['store_id'], $base64Image);
            if (empty($logoPath)) {
                throw new InvalidFileException($this->lang->noImageCreated(), Http::SERVER_ERROR);
            }

            $storeInfoDAO->putStoreLogo($loginParams['store_id'], $logoPath);

            $arrayReturn = [
                'url_image' => $imageManager->makeStoreFolderPath($loginParams['store_id']) . '/' . $logoPath
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
}