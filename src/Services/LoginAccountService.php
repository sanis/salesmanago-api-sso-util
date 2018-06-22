<?php

namespace SALESmanago\Services;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Entity\Settings;


class LoginAccountService extends AbstractClient implements LoginAccountInterface, UserCustomPropertiesInterface
{
    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    /**
     * @throws SalesManagoException
     * @param array $user
     * @return array
     */
    public function accountAuthorize($user = array())
    {
        $data = array(
            'username' => $user['username'],
            'password' => $user['password'],
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_LOGIN_AUTHORIZE, $data);
        return $this->validateCustomResponse($response, array(array_key_exists(Settings::TOKEN, $response)));
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function accountIntegrationSettings(Settings $settings)
    {
        $this->setClient($settings);

        $data = array(
            Settings::TOKEN => $settings->getToken(),
            Settings::API_KEY => $settings->getApiKey(),
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_ACCOUNT_INTEGRATION, $data);
        return $this->validateCustomResponse($response, array(array_key_exists('shortId', $response)));
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function getUserCustomProperties(Settings $settings)
    {
        $data = array(
            Settings::TOKEN => $settings->getToken(),
            Settings::CLIENT_ID => $settings->getClientId()
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_GET_INTEGRATION_PROPERTIES, $data);
        if (isset($response["properties"])) {
            $response["properties"] = json_decode($response["properties"], true);
        }
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $properties
     * @return array
     */
    public function setUserCustomProperties(Settings $settings, $properties)
    {
        $data = array(
            Settings::TOKEN => $settings->getToken(),
            Settings::CLIENT_ID => $settings->getClientId(),
            "properties" => json_encode($properties)
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_SET_INTEGRATION_PROPERTIES, $data);
        return $this->validateResponse($response);
    }
}