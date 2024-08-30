<?php

namespace App\Services;

use App\DAO\StoreDAO;
use App\DAO\StoreInfoDAO;
use App\Exceptions\InvalidInputException;
use App\Exceptions\UploadException;
use App\Helpers\Http;
use App\Helpers\ImageManager;
use App\Helpers\Utils;
use App\Helpers\Validator;
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
            
            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            if (!is_array($params) || !Validator::arrayKeysExists(['base64Image'], $params)) {
                throw new InvalidInputException($this->lang->invalidRequestParams(), Http::BAD_REQUEST);
            }

            $imageManager = new ImageManager();
            $storeInfoDAO = new StoreInfoDAO();

            $pathImageSaved = $storeInfoDAO->getStorePathLogo($loginParams['store_id'])[0]['store_path_logo'];
            if ($pathImageSaved) {
                $repository = $imageManager->makeStoreFolderPath($loginParams['store_id']);
                $imagePath  = $repository . '/' . $pathImageSaved;
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

    public function updateStore(Request $request, Response $response, array $args) : Response {
        try {
            $params = $request->getParsedBody();
            $loginParams = $request->getAttribute('jwt');

            if (!$params || !$loginParams) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            $fieldsToUpdate = [];
            if (isset($params['name'])) {
                $name = Utils::removeDoubleSpaces($params['name']);
                if (!Validator::validateName($params['name'])) {
                    throw new InvalidInputException($this->lang->invalidStoreName(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['name'] = $name;
            }
    
            if (isset($params['email'])) {
                if (!Validator::validateEmail($params['email'])) {
                    throw new InvalidInputException($this->lang->invalidStoreEmail(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['email'] = $params['email'];
            }
    
            if (isset($params['cnpj'])) {
                $cnpj = Utils::filterNumbersOnly($params['cnpj']);
                if (!Validator::validateCNPJ($cnpj)) {
                    throw new InvalidInputException($this->lang->invalidCNPJ(), Http::BAD_REQUEST);
                }
                $fieldsToUpdate['cnpj'] = $cnpj;
            }
    
            if (isset($params['lat']) && isset($params['lon'])) {
                $fieldsToUpdate['lat'] = $params['lat'];
                $fieldsToUpdate['lon'] = $params['lon'];
            }

            if (empty($fieldsToUpdate)) {
                throw new InvalidInputException($this->lang->noDataToUpdate(), Http::BAD_REQUEST);
            }

            $fieldsToUpdate['store_id'] = $loginParams['store_id'];

            $storeInfoDAO = new StoreInfoDAO();
            $storeInfoDAO->updateStore($fieldsToUpdate);

            return Http::getJsonReponseSuccess($response, [], $this->lang->storeInformationRegistered(), Http::CREATED);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function getStore(Request $request, Response $response, array $args) : Response {
        try {

            $storeId = (int) Utils::filterNumbersOnly($args['id']);
            if (empty($storeId) || !Validator::validateId($storeId)) {
                throw new InvalidInputException($this->lang->invalidId(), Http::BAD_REQUEST);
            }

            $dao = new StoreInfoDAO();
            $storeInfo = $dao->getStoreInformationByStoreId($storeId);

            $storeData = [];
            if (!empty($storeInfo)) {
                $imageManager = new ImageManager();
                $logoPath = $imageManager->makeStoreFolderPath($storeId);

                if ($storeInfo['store_path_logo']) {
                    $logoPath .= '/' . $storeInfo['store_path_logo'];
                } else {
                    $logoPath = $imageManager->makeUrlNoImage();
                }

                $storeData = [
                    'id' => $storeInfo['store_info_store_id'],
                    'name' => $storeInfo['store_name'],
                    'email' => $storeInfo['store_email'],
                    'logo' => $logoPath,
                    'latitude' => $storeInfo['lat'],
                    'longitude' => $storeInfo['lon'],
                ];
            }

            return Http::getJsonReponseSuccess($response, $storeData, $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function getNearbyStores(Request $request, Response $response, array $args) : Response {
        try {

            $params = $request->getParsedBody();
            if (!$params) {
                throw new InvalidInputException($this->lang->notParamsDetected(), Http::BAD_REQUEST);
            }

            if (!is_array($params) || !Validator::arrayKeysExists(['lat', 'lon', 'radius'], $params)) {
                throw new InvalidInputException($this->lang->invalidRequestParams(), Http::BAD_REQUEST);
            }

            $latitude = Utils::convertToFloat($params['lat']);
            $longitude = Utils::convertToFloat($params['lon']);
            $radius = Utils::convertToFloat($params['radius']);

            $dao = new StoreInfoDAO();
            $stores = $dao->findStoresByCoordinates($latitude, $longitude, $radius);

            return Http::getJsonReponseSuccess($response, $stores, $this->lang->success(), Http::OK);

        } catch (InvalidInputException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());
        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}