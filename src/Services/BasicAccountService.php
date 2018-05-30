<?php

namespace SALESmanago\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;


class BasicAccountService
{
    const METHOD_CREATE_LIVE_CHAT = "/api/wm/createLiveChat",
          METHOD_CREATE_BASIC_POPUP = "/api/wm/createBasicPopup",
          METHOD_CREATE_WEB_PUSH_CONSENT = "/api/wm/createWebPushConsentForm",
          METHOD_CREATE_WEB_PUSH_NOTIFICATION = "/api/wm/createWebPushNotification",
          METHOD_CREATE_WEB_PUSH_CONSENT_AND_NOTIFICATION = "/api/wm/createWebPushConsentFormAndNotification",
          METHOD_GET_INTEGRATION_PROPERTIES = '/api/account/integration/properties',
          METHOD_SET_INTEGRATION_PROPERTIES = '/api/account/integration/setProperties',
          METHOD_ADD_SUBSCRIBE_PRODUCTS = "/api/appstore/subscribeProducts",
          METHOD_GET_ACCOUNT_ITEMS = "/api/account/items",

          REDIRECT_APP = "/api/authorization/authorize",
          REFRESH_TOKEN = "/api/authorization/refreshToken";

    /** @var GuzzleClient $guzzle */
    protected $guzzle;

    protected $modules = array("EMAIL_MARKETING","LIVE_CHAT", "WEB_PUSH", "CF_P_LP");

    /**
     * instantiate guzzle connection
     * @var Settings $settings
     * @return GuzzleClient
     */
    protected function getGuzzleClient(Settings $settings)
    {
        if (!$this->guzzle) {
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
        }
        return $this->guzzle;
    }

    protected function __getDefaultApiData(Settings $settings)
    {
        $data = array(
            'clientId' => $settings->getClientId(),
            'apiKey' => $settings->getApiKey(),
            'requestTime' => time(),
            'sha' => $settings->getSha(),
            'owner' => $settings->getOwner()
        );
        return $data;
    }

    protected function __getModulesData($modulesId)
    {
        $modules = array();

        foreach ($modulesId as $value) {
            $obj = array("name" => $this->modules[$value]);

            if ($value == 0) {
                $obj = array_merge($obj, array(
                    "contactLimit"=> 1000
                ));
            }
            array_push($modules, $obj);
        }

        return $modules;
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

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_GET_ACCOUNT_ITEMS, array(
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

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::REFRESH_TOKEN, array(
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

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function getIntegrationProperties(Settings $settings)
    {
        $data = array(
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId()
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_GET_INTEGRATION_PROPERTIES, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && $response['success'] == true
            ) {
                $response['properties'] = json_decode($response['properties'], true);
                return $response;
            } else {
//                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
                return $response;
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
     * @param string $properties
     * @return array
     */
    public function setIntegrationProperties(Settings $settings, $properties)
    {
        $data = array(
            "token" => $settings->getToken(),
            "clientId" => $settings->getClientId(),
            "properties" => json_encode($properties)
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_SET_INTEGRATION_PROPERTIES, array(
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

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', $method, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && $response['success'] == true
            ) {
                $item = array(
                    "success" => true,
                );
                return $item;
            } elseif (is_array($response)
                && array_key_exists('success', $response)
                && $response['success'] == false
                && $response['message'][1] == 'STORE'
            ) {
                $response = array(
                    'success' => false,
                    'type' => $response['message'][1]
                );
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

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $modulesId
     * @return string
     */
    public function addSubscribeProducts(Settings $settings, $modulesId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'items' => json_encode($this->__getModulesData($modulesId))
        ));

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_ADD_SUBSCRIBE_PRODUCTS, array(
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
}