<?php

namespace SALESmanago\Services;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Entity\Settings;


class LoginAccountAccountService extends AbstractClient implements LoginAccountInterface, UserCustomPropertiesInterface
{
    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    /**
     * @throws SalesManagoException
     * @param array $user
     */
    public function accountAuthorize($user = array())
    {
        $data = array(
            'username' => $user['username'],
            'password' => $user['password'],
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_LOGIN_AUTHORIZE, $data);
        $this->validateCustomResponse($response, array(array_key_exists('token', $response)));
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     */
    public function accountIntegrationSettings(Settings $settings)
    {
        $this->setClient($settings);

        $data = array(
            'token' => $settings->getToken(),
            'apiKey' => $settings->getApiKey(),
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_ACCOUNT_INTEGRATION, $data);
        $this->validateCustomResponse($response, array(array_key_exists('shortId', $response)));
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     */
    public function getUserCustomProperties(Settings $settings)
    {
        $data = array(
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId()
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_GET_INTEGRATION_PROPERTIES, $data);
        $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $properties
     */
    public function setUserCustomProperties(Settings $settings, $properties)
    {
        $data = array(
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId(),
            "properties" => json_encode($properties)
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_SET_INTEGRATION_PROPERTIES, $data);
        $this->validateResponse($response);
    }
}