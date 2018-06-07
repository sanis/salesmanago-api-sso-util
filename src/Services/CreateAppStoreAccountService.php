<?php

namespace SALESmanago\Services;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Entity\Settings;


class CreateAppStoreAccountService extends BasicAccountService
{
    const METHOD_CREATE_ACCOUNT = "/api/account/registerAppstore",
          METHOD_CONTACT_SUPPORT = "/api/contact/upsertVendorToSupport";

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $user
     * @param array $modulesId
     * @return array
     */
    public function createAccount(Settings $settings, $user, $modulesId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'email' => $user['email'],
            'password' => $user['password'],
            'lang' => $user['lang'],
            'items' => json_encode($this->__getModulesData($modulesId))
        ));

        if (isset($user['tags'])) {
            $data['tags'] = $user['tags'];
        }

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_CREATE_ACCOUNT, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && $response['success'] == true
            ) {
                 return $response;
            } else {
                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(),0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        }
    }


    public function contactToSupport($settings)
    {
        $data = $this->__getDefaultApiData($settings);

        if (count($settings->getTags()) > 0) {
            $tag['tags'] = $settings->getTags();
        }

        if (count($settings->getRemoveTags()) > 0) {
            $tag['removeTags'] = $settings->getRemoveTags();
        }

        if (count($settings->getProperties()) > 0) {
            $tag['properties'] = $settings->getProperties();
        }

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_CONTACT_SUPPORT, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && array_key_exists('contactId', $response)
                && $response['success'] == true
            ) {
                return $response;
            } else {
                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        }
    }

    public function getContactIp() {
        if (getenv('HTTP_CLIENT_IP'))
            $ip = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ip = getenv('HTTP_FORWARDED');
        else
            $ip = getenv('REMOTE_ADDR');

        return $ip;
    }

}