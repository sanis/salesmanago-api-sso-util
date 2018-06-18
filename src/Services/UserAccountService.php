<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoException;


class UserAccountService extends AbstractClient implements UserCustomPropertiesInterface
{
    const METHOD_CREATE_LIVE_CHAT = "/api/wm/createLiveChat",
          METHOD_CREATE_BASIC_POPUP = "/api/wm/createBasicPopup",
          METHOD_CREATE_WEB_PUSH_CONSENT = "/api/wm/createWebPushConsentForm",
          METHOD_CREATE_WEB_PUSH_NOTIFICATION = "/api/wm/createWebPushNotification",
          METHOD_CREATE_WEB_PUSH_CONSENT_AND_NOTIFICATION = "/api/wm/createWebPushConsentFormAndNotification",

          METHOD_ADD_SUBSCRIBE_PRODUCTS = "/api/appstore/subscribeProducts",
          METHOD_ACCOUNT_ITEMS = "/api/account/items",

          REDIRECT_APP = "/api/authorization/authorize",
          REFRESH_TOKEN = "/api/authorization/refreshToken";

    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    protected function __getLiveChatData($options)
    {
        $data = array(
            'chat' => array(
                'name' => 'SSO Live Chat' . date(' Y-m-d H:i:s', time()),
                "defaultConsultant" => array(
                    "name" => $options['consultant'],
                    "avatar" => array(
                        "url" => "https://s3-eu-west-1.amazonaws.com/salesmanago/chat/default_avatar.png"
                    )
                ),
                'colors' => array(
                    'main' => $options['color']['main'],
                    'mainFont' => $options['color']['mainFont'],
                    'additional' => $options['color']['additional'],
                    'additionalFont' => $options['color']['additionalFont'],
                    'background' => '#ffffff',
                    'info' => '#9c9c9c'
                ),
                'active' => true,
                'contactOwner' => array(
                    'email' => $options['email']
                )
            )
        );
        return $data;
    }

    protected function __getBasicPopupData($options)
    {
        $data = array (
            'popup' => array (
                'popupType' => 'NEW_VISITORS_CONTACT_FORM',
                'contactFormContent' => array (
                    'image' =>
                        array (
                            'fileName' => 'logo.png',
                            'url' => 'https://s3-eu-west-1.amazonaws.com/salesmanagoimg/0inieufi69duje4c/anvpxf3d6h1bdz7w/m32izi3c5cfr1fio.png',
                            'cssClass' => 'fa-envelope',
                        ),

                    'title' =>
                        array (
                            'text' => 'Pierwszy raz u nas?',
                            'fontColor' => '#ffffff',
                        ),
                    'content' =>
                        array (
                            'text' => 'Zostaw kontakt — odezwiemy się.',
                            'fontColor' => '#ffffff ',
                            'align ' => 'left ',
                        ),
                    'inputFields' => array (
                        array (
                            'label' =>
                                array (
                                    'text' => 'Imię i nazwisko',
                                    'fontColor' => '#ffffff',
                                ),
                            'type' => 'NAME',
                            'customDetailName' => NULL,
                            'placeholder' =>
                                array (
                                    'text' => 'Jan Kowalski',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag' => NULL,
                            'declinedTag' => NULL,
                            'required' => false,
                        ),
                        array (
                            'label' =>
                                array (
                                    'text' => 'Adres e-mail',
                                    'fontColor' => '#ffffff',
                                ),
                            'type' => 'EMAIL',
                            'customDetailName' => NULL,
                            'placeholder' =>
                                array (
                                    'text' => 'email.address@example.com',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag' => NULL,
                            'declinedTag' => NULL,
                            'required' => false,
                        ),
                        array (
                            'label' =>
                                array (
                                    'text' => 'Firma',
                                    'fontColor' => '#ffffff',
                                ),
                            'type' => 'COMPANY',
                            'customDetailName' => NULL,
                            'placeholder' =>
                                array (
                                    'text' => 'Nazwa Twojej firmy',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag' => NULL,
                            'declinedTag' => NULL,
                            'required' => false,
                        ),
                        array (
                            'label' =>
                                array (
                                    'text' => 'Strona WWW',
                                    'fontColor' => '#ffffff',
                                ),
                            'type' => 'WEBSITE',
                            'customDetailName' => NULL,
                            'placeholder' =>
                                array (
                                    'text' => 'Adres Twojej strony',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag' => NULL,
                            'declinedTag' => NULL,
                            'required' => false,
                        ),
                    ),
                    'backgroundColor' => '#5e1212',
                    'subscribeButton' =>
                        array (
                            'text' => 'OK',
                            'borderRadius' => '5px',
                            'fontColor' => '#3b2727',
                            'backgroundColor' => '#ebebeb',
                        ),
                ),
                'settings' => array(
                    'confirmationEmailAccount' => array(
                        'email' => $options['email']
                    ),
                    'popupName' => 'Popup API' . date(' Y-m-d H:i:s', time()),
                    'subscribedTag' => 'moj-popup-zapis',
                    'confirmedTag' => 'moj-popup-potwierdzenie',
                    'hideOnMobileDevices' => false,
                    'hideForMonitoredContacts' => false,
                    'displayWhen' => array (
                        'mode' => 'AFTER_X_SECONDS',
                        'siteScrollPercent' => 25,
                        'pageViews' => 1,
                        'secondsSinceVisit' => 5,
                    ),
                    'displayWhere' => array (
                        'mode' => 'ON_ALL_PAGES',
                        'domain' => '**',
                        'containedPhrases' => [],
                    ),
                ),
                'completedSteps' => 5,
                'active' => true
            )
        );

        return $data;
    }

    protected function __getWebPushConsentData($options)
    {
        $data = array(
            "webPushConsentForm" => array(
                "name" => $options['title'] . date(' Y-m-d H:i:s', time()),
                "tags" => [],
                "consentForm" => array(
                    "title" => $options['title'],
                    "body" => $options['body'],
                    "imgUrl" => $options['logo'],
                    "thanksText" => $options['thanksText'],
                    "thanksUrl" => $options['thanksUrl']
                ),
                "buttonSettings" => array(
                    "confirmationBackgroundColor" => $options['color']['main'],
                    "confirmationText" => $options['confirmationText'],
                    "confirmationTextColor" => $options['color']['mainFont'],
                    "rejectionText" => $options['rejectionText']
                ),
                "consentFormId" => null,
                "consentFormSize" => "MEDIUM",
                "marginTop" => 0,
                "active" => true
            )
        );
        return $data;
    }

    protected function __getWebPushNotificationData($options)
    {
        $data = array(
            "webPushNotification" => array(
                "name" => "Web Push 2017-11-15 16:08:24",
                "webPushNotification" => array(
                    "title" => "Witaj!",
                    "body" => "Mamy coś specjalnie dla Ciebie. Odwiedź naszą stronę.",
                    "imgUrl" => "https://s3-eu-west-1.amazonaws.com/salesmanagoimg/ye4vodnswfo6zp75/36m0iryqk4wlt6wu/vi3qhhiwqc485flt.png",
                    "targetUrl" => "https://www.domain.com/target_url"
                ),
                "richWebPush" => array(
                    "title" => null,
                    "body" => null,
                    "iconUrl" => null,
                    "targetUrl" => null,
                    "imageUrl" => null
                ),
                "receivers" => ".AllContacts",
                "sendingDate" => 1510704000000,
                "selectedConsentForms" => [123],
                "ttl" => array(
                    "timestamp" => "WEEKS",
                    "value" => 2419200
                ),
                "tags" => [],
                "excludedTags" => [],
                "webPushType" => "WEB_PUSH",
                "active" => true,
                "showWebPush" => true
            )
        );
        return $data;
    }

    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function userItems(Settings $settings)
    {
        $data = array(
            "token" => $settings->getToken(),
            "apiKey" => $settings->getApiKey()
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_ACCOUNT_ITEMS, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function refreshToken(Settings $settings)
    {
        $data = array(
            "token" => $settings->getToken()
        );

        $response = $this->request(self::METHOD_POST, self::REFRESH_TOKEN, $data);
        return $this->validateResponse($response);
    }

    /**
     * @var Settings $settings
     * @return string
     */
    public function redirectApp(Settings $settings)
    {
        return $settings->getRequestEndpoint() . self::REDIRECT_APP . '?t=' . $settings->getToken();
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $method
     * @param array $options
     * @return array
     */
    public function createProduct(Settings $settings, $method, $options = array())
    {
        switch ($method) {
            case self::METHOD_CREATE_LIVE_CHAT:
                $productProperties = $this->__getLiveChatData($options);
                break;
            case self::METHOD_CREATE_BASIC_POPUP:
                $productProperties = $this->__getBasicPopupData($options);
                break;
            case self::METHOD_CREATE_WEB_PUSH_CONSENT:
                $productProperties = $this->__getWebPushConsentData($options);
                break;
            case self::METHOD_CREATE_WEB_PUSH_NOTIFICATION:
                $productProperties = $this->__getWebPushNotificationData($options);
                break;
            case self::METHOD_CREATE_WEB_PUSH_CONSENT_AND_NOTIFICATION:
                $productProperties = array_merge(
                    $this->__getWebPushConsentData($options),
                    $this->__getWebPushNotificationData($options)
                );
                break;
            default:
                throw new SalesManagoException('Unsupported data source type', 13);
        }

        $data = array_merge(
            $this->__getDefaultApiData($settings),
            $productProperties
        );

        $response = $this->request(self::METHOD_POST, $method, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $modulesId
     * @return array
     */
    public function addSubscribeProducts(Settings $settings, $modulesId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'items' => json_encode($this->__getModulesData($modulesId))
        ));

        $response = $this->request(self::METHOD_POST, self::METHOD_ADD_SUBSCRIBE_PRODUCTS, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function getUserCustomProperties(Settings $settings)
    {
        $data = array(
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId()
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_GET_INTEGRATION_PROPERTIES, $data);
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
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId(),
            "properties" => json_encode($properties)
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_SET_INTEGRATION_PROPERTIES, $data);
        return $this->validateResponse($response);
    }
}