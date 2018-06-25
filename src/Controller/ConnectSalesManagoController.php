<?php

namespace SALESmanago\Controller;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Services\ConnectSalesManagoService;
use SALESmanago\Entity\Settings;


class ConnectSalesManagoController
{
    const COOKIES_CLIENT = "smclient",
          COOKIES_EXT_EVENT = "smevent";

    protected $settings;
    protected $service;


    public function __construct(Settings $settings)
    {
        $this->service = new ConnectSalesManagoService($settings);
        $this->settings = $settings;
    }

    public function createCookie($name, $value)
    {
        $period = time() + (3600 * 86400);
        setcookie($name, $value, $period, '/');
    }
    public function deleteCookie($name)
    {
        unset($_COOKIE[$name]);
        setcookie($name, null, -1, '/');
    }

    public function contactUpsert($user, $options = array(), $properties = array())
    {
        try {
            $responseData = $this->service->contactUpsert($this->settings, $user, $options, $properties);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function contactDelete($userEmail)
    {
        try {
            $responseData = $this->service->contactDelete($this->settings, $userEmail);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function getContactByEmail($userEmail)
    {
        try {
            $responseData = $this->service->getContactByEmail($this->settings, $userEmail);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function contactExtEvent($type, $product, $user, $eventId = null)
    {
        try {
            $eventTypeName = $this->service->checkAccessEventType($type);
            $responseData = $this->service->contactExtEvent($this->settings, $eventTypeName, $product, $user, $eventId);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

}