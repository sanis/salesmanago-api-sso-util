<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Settings;
use SALESmanago\Services\BasicAccountService;
use SALESmanago\Exception\SalesManagoException;


class BasicAccountController
{
    protected $settings;
    protected $service;

    public function __construct(Settings $settings)
    {
        $this->service = new BasicAccountService();
        $this->settings = $settings;
    }

    public function refreshToken()
    {
        try {
            $responseData = $this->service->refreshToken($this->settings);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function getIntegrationProperties()
    {
        try {
            $responseData = $this->service->getIntegrationProperties($this->settings);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function setIntegrationProperties($properties)
    {
        try {
            $responseData = $this->service->setIntegrationProperties($this->settings, $properties);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function getUserItems()
    {
        try {
            $responseData = $this->service->userItems($this->settings);
            $responseData['redirect'] = $this->getRedirectApp();
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function getRedirectApp()
    {
        $responseData = $this->service->redirectApp($this->settings);
        return $responseData;
    }

    public function addSubscribeProducts($module_name)
    {
        try {

            $products = $this->service->getModules();

            if(in_array($module_name, $products)) {
                $modulesId = array(
                    0 => array_search($module_name, $products)
                );
            } else {
                throw new SalesManagoException('Cannot add appstore products', 13);
            }

            $responseData = $this->service->addSubscribeProducts($this->settings, $modulesId);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function createProduct($module_name, $properties)
    {
        try {
            switch ($module_name) {
                case "LIVE_CHAT":
                    $response = $this->service->createProduct(
                        $this->settings,
                        BasicAccountService::METHOD_CREATE_LIVE_CHAT,
                        array_merge(
                            $properties,
                            array(
                                'email' => $this->settings->getOwner()
                            )
                        )
                    );
                    break;
                case "WEB_PUSH":
                    $response = $this->service->createProduct(
                        $this->settings,
                        BasicAccountService::METHOD_CREATE_WEB_PUSH_CONSENT,
                        array_merge(
                            $properties,
                            array(
                                'logo' => 'https://s3-eu-west-1.amazonaws.com/salesmanagoimg/ye4vodnswfo6zp75/36m0iryqk4wlt6wu/vi3qhhiwqc485flt.png',
                                'thanksUrl' => 'https://www.domain.com/target_url',
                                'email' => $this->settings->getOwner()
                            )
                        )
                    );
                    break;
                case "CF_P_LP":
                    $response = $this->service->createProduct(
                        $this->settings,
                        BasicAccountService::METHOD_CREATE_BASIC_POPUP,
                        array_merge(
                            $properties,
                            array(
                                'email' => $this->settings->getOwner()
                            )
                        )
                    );
                    break;
                default:
                    throw new SalesManagoException('Unsupported data source type', 13);
            }

            if (is_array($response)
                && $response['success'] == false
                && $response['type'] == 'STORE'
            ) {
                $this->addSubscribeProducts($module_name);
                return $this->createProduct($module_name, $properties);
            }

            return $response;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }
}