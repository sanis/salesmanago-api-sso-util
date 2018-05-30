<?php

namespace SALESmanago\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Entity\Settings;


class LoginAccountService extends BasicAccountService
{
    const METHOD_LOGIN_AUTHORIZE = "/api/authorization/token",
          METHOD_ACCOUNT_INTEGRATION = "/api/account/integration";

    protected function getGuzzleClient(Settings $settings)
    {
        $this->guzzle = new GuzzleClient([
            'base_uri' => $settings->getRequestEndpoint(),
            'verify' => false,
            'timeout'  => 45.0,
            'defaults' => [
                'headers' => [
                    'Accept' => 'application/json, application/json',
                    'Content-Type' => 'application/json;charset=UTF-8'
                ],
            ]
        ]);

        return $this->guzzle;
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $user
     * @return array
     */
    public function accessToken(Settings $settings, $user = array())
    {
        $data = array(
            'username' => $user['username'],
            'password' => $user['password'],
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_LOGIN_AUTHORIZE, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && array_key_exists('token', $response)
                && $response['success'] == true
            ) {
                return $response;
            } else {
                $error = array(
                    'success' => false,
                    'message' => $response['message'],
                    'status' => $guzzleResponse->getStatusCode()

                );
                return $error;
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

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function integrationSettings(Settings $settings)
    {
        $data = array(
            'token' => $settings->getToken(),
            'apiKey' => $settings->getApiKey(),
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_ACCOUNT_INTEGRATION, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && array_key_exists('shortId', $response)
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
}