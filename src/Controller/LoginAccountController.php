<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Settings;
use SALESmanago\Services\LoginAccountService;
use SALESmanago\Exception\Exception;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\DependencyManagement\IoC as Container;


class LoginAccountController
{
    protected $settings;
    protected $service;

    public function __construct(Settings $settings)
    {
        $this->service = new LoginAccountService();
        $this->settings = $settings;
    }

    public function accessToken($user)
    {
        try {
            $responseData = $this->service->accessToken($this->settings, $user);

            $container = Container::init();
            $settings = $this->settings;

            if ($responseData['success'] == true) {

                $container::extend("user-settings", function () use ($settings, $user, $responseData) {
                    $settings
                        ->setOwner($user['username'])
                        ->setDefaultApiKey()
                        ->setToken($responseData['token'])
                        ->setEndpoint($responseData['endpoint']);
                    return $settings;
                });

                $this->integrationSettings();

                $integration = $this->service->getIntegrationProperties($this->settings);

                if ($integration['success'] == true) {
                    $data = array(
                        'success' => true,
                        'token' => $responseData['token'],
                        'properties' => array(
                            'success' => true,
                            'lang' => $integration['properties']['lang'],
                            'color' => $integration['properties']['color']
                        )
                    );
                } else {
                    $data = array(
                        'success' => true,
                        'token' => $responseData['token'],
                        'properties' => array(
                            'success' => false
                        )
                    );
                }

                return $data;
            } else {
                throw SalesManagoError::handleError($responseData['message'], $responseData['status']);
            }

        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
//        catch (Exception $e) {
//            return $e->getMessage();
//        }
    }

    /**
     * @throws Exception
     **/
    protected function integrationSettings()
    {
        try {
            $responseData = $this->service->integrationSettings($this->settings);

            $this->settings
                ->setClientId($responseData['shortId'])
                ->setSha($responseData['sha1']);
        } catch (SalesManagoException $e) {
            throw new Exception(json_encode($e->getSalesManagoMessage()));
        }
    }
}