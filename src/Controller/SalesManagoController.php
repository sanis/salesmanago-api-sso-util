<?php

namespace SALESmanago\Controller;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Services\SalesManagoService;
use SALESmanago\Entity\Settings;


class SalesManagoController
{
    const COOKIES_CLIENT = "smclient",
          COOKIES_EXT_EVENT = "smevent";

    protected $settings;
    protected $service;

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

    public function __construct(Settings $settings)
    {
        $this->service = new SalesManagoService();
        $this->settings = $settings;
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

    public function getContactId($userEmail)
    {
        try {
            $responseData = $this->service->getContactId($this->settings, $userEmail);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function accountItems()
    {
        try {
            $responseData = $this->service->accountItems($this->settings);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function exportContacts()
    {
        $data = array();
        $users = array();
        $options = array();
        $properties = array();

        foreach ($users as $user) {
            array_push(
                $data,
                $this->service->prepareContactsDetails($user, $options, $properties)
            );
        }
        try {
            $response = $this->service->exportContacts($this->settings, $data);
            return json_encode($response);
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

    public function exportContactExtEvents()
    {
        $data = array();
        $orders= array();
        $user = array();

        foreach ($orders as $order) {
            array_push(
                $data,
                $this->service->prepareContactEvents(SalesManagoService::EVENT_TYPE_CART, $order, $user)
            );
        }
        try {
            $response = $this->service->exportContactExtEvents($this->settings, $data);
            return json_encode($response);
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }
    }

    public function createMonitorVisitorsCode($webPush='', $shopId)
    {
        $endpoint = $this->settings->getEndpoint();
        $code = "<script type=\"text/javascript\">
var _smid = '" . $this->settings->getClientId() . "';
(function(w, r, a, sm, s ) {
    w['SalesmanagoObject'] = r;
    w[r] = w[r] || function () {( w[r].q = w[r].q || [] ).push(arguments)};
    sm = document.createElement('script');
    sm.type = 'text/javascript'; sm.async = true; sm.src = a;
    s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(sm, s);
 })(window, 'sm', ('https:' == document.location.protocol ? 'https://' : 'http://') 
 + '" . $endpoint . "/static/sm.js');
 {$webPush}</script>";

        return $code;
    }
}