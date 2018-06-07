<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Settings;
use SALESmanago\Services\CreateAppStoreAccountService;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Provider\UserProvider;


class CreateAccountController
{
    protected $settings;
    protected $service;

    public function __construct(Settings $settings)
    {
        $this->service = new CreateAppStoreAccountService();
        $this->settings = $settings;
    }

    public function refreshToken($user)
    {
        try {
            $this->settings->setToken(
                $this->service->refreshToken($this->settings, $user)
            );

            $responseData = array(
                'success' => true
            );

            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function createAccount($user, $modulesId)
    {
        try {
            $userData = $this->service->createAccount($this->settings, $user, $modulesId);

            $userData['endpoint'] = $this->settings->getEndpoint();

            if (isset($user['email'])) {
                $userData['email'] = $user['email'];
            }

            UserProvider::createSettingsContainer("user-settings", $userData);

            $settings = UserProvider::getSettingsContainer("user-settings");
            $settings->setTags(($user['lang'] == "PL") ? "SALESMANAGO-R-B2C-SSO_PL" : "SALESMANAGO-R-B2C-SSO_ZG");

            $this->service->contactToSupport($settings);

            $responseData = array(
                'success' => $userData['success'],
                'message' => $userData['message'],
                'token' => $userData['token']
            );

            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }
}