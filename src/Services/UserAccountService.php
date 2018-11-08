<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoException;


class UserAccountService extends AbstractClient implements UserAccountInterface, UserCustomPropertiesInterface
{
    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    protected function __getLiveChatData($name, $options)
    {
        $data = array(
            'chat' => array(
                'name'              => $name,
                "defaultConsultant" => array(
                    "name"   => $options['consultant'],
                    "avatar" => array(
                        "url" => "https://s3-eu-west-1.amazonaws.com/salesmanago/chat/default_avatar.png"
                    )
                ),
                'colors'            => array(
                    'main'           => $options['color']['main'],
                    'mainFont'       => $options['color']['mainFont'],
                    'additional'     => $options['color']['additional'],
                    'additionalFont' => $options['color']['additionalFont'],
                    'background'     => '#ffffff',
                    'info'           => '#9c9c9c'
                ),
                'active'            => true,
                'contactOwner'      => array(
                    'email' => $options['email']
                )
            )
        );
        return $data;
    }

    protected function __getBasicPopupData($name, $options)
    {
        $data = array(
            'popup' => array(
                'popupType'          => 'NEW_VISITORS_CONTACT_FORM',
                'contactFormContent' => array(
                    'image' =>
                        array(
                            'fileName' => 'logo.png',
                            'url'      => 'https://s3-eu-west-1.amazonaws.com/salesmanagoimg/0inieufi69duje4c/anvpxf3d6h1bdz7w/m32izi3c5cfr1fio.png',
                            'cssClass' => 'fa-envelope',
                        ),

                    'title'           =>
                        array(
                            'text'      => 'Pierwszy raz u nas?',
                            'fontColor' => '#ffffff',
                        ),
                    'content'         =>
                        array(
                            'text'      => 'Zostaw kontakt — odezwiemy się.',
                            'fontColor' => '#ffffff ',
                            'align '    => 'left ',
                        ),
                    'inputFields'     => array(
                        array(
                            'label'            =>
                                array(
                                    'text'      => 'Imię i nazwisko',
                                    'fontColor' => '#ffffff',
                                ),
                            'type'             => 'NAME',
                            'customDetailName' => NULL,
                            'placeholder'      =>
                                array(
                                    'text'      => 'Jan Kowalski',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag'  => NULL,
                            'declinedTag'      => NULL,
                            'required'         => false,
                        ),
                        array(
                            'label'            =>
                                array(
                                    'text'      => 'Adres e-mail',
                                    'fontColor' => '#ffffff',
                                ),
                            'type'             => 'EMAIL',
                            'customDetailName' => NULL,
                            'placeholder'      =>
                                array(
                                    'text'      => 'email.address@example.com',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag'  => NULL,
                            'declinedTag'      => NULL,
                            'required'         => false,
                        ),
                        array(
                            'label'            =>
                                array(
                                    'text'      => 'Firma',
                                    'fontColor' => '#ffffff',
                                ),
                            'type'             => 'COMPANY',
                            'customDetailName' => NULL,
                            'placeholder'      =>
                                array(
                                    'text'      => 'Nazwa Twojej firmy',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag'  => NULL,
                            'declinedTag'      => NULL,
                            'required'         => false,
                        ),
                        array(
                            'label'            =>
                                array(
                                    'text'      => 'Strona WWW',
                                    'fontColor' => '#ffffff',
                                ),
                            'type'             => 'WEBSITE',
                            'customDetailName' => NULL,
                            'placeholder'      =>
                                array(
                                    'text'      => 'Adres Twojej strony',
                                    'fontColor' => NULL,
                                ),
                            'confirmationTag'  => NULL,
                            'declinedTag'      => NULL,
                            'required'         => false,
                        ),
                    ),
                    'backgroundColor' => '#5e1212',
                    'subscribeButton' =>
                        array(
                            'text'            => 'OK',
                            'borderRadius'    => '5px',
                            'fontColor'       => '#3b2727',
                            'backgroundColor' => '#ebebeb',
                        ),
                ),
                'settings'           => array(
                    'confirmationEmailAccount' => array(
                        'email' => $options['email']
                    ),
                    'popupName'                => $name,
                    'subscribedTag'            => 'moj-popup-zapis',
                    'confirmedTag'             => 'moj-popup-potwierdzenie',
                    'hideOnMobileDevices'      => false,
                    'hideForMonitoredContacts' => false,
                    'displayWhen'              => array(
                        'mode'              => 'AFTER_X_SECONDS',
                        'siteScrollPercent' => 25,
                        'pageViews'         => 1,
                        'secondsSinceVisit' => 5,
                    ),
                    'displayWhere'             => array(
                        'mode'             => 'ON_ALL_PAGES',
                        'domain'           => '**',
                        'containedPhrases' => [],
                    ),
                ),
                'completedSteps'     => 5,
                'active'             => true
            )
        );

        return $data;
    }

    protected function __getWebPushConsentData($name, $options)
    {
        $data = array(
            "webPushConsentForm" => array(
                "name"            => $name,
                "tags"            => [],
                "consentForm"     => array(
                    "title"      => $options['title'],
                    "body"       => $options['body'],
                    "imgUrl"     => $options['logo'],
                    "thanksText" => $options['thanksText'],
                    "thanksUrl"  => $options['thanksUrl']
                ),
                "buttonSettings"  => array(
                    "confirmationBackgroundColor" => $options['color']['main'],
                    "confirmationText"            => $options['confirmationText'],
                    "confirmationTextColor"       => $options['color']['mainFont'],
                    "rejectionText"               => $options['rejectionText']
                ),
                "consentFormId"   => null,
                "consentFormSize" => "MEDIUM",
                "marginTop"       => 0,
                "active"          => true
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
            Settings::TOKEN   => $settings->getToken(),
            Settings::API_KEY => $settings->getApiKey()
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
            Settings::TOKEN => $settings->getToken()
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
                $name = 'SSO Live Chat' . date(' Y-m-d H:i:s', time());
                $productProperties = $this->__getLiveChatData($name, $options);
                break;
            case self::METHOD_CREATE_BASIC_POPUP:
                $name = 'SSO Popup' . date(' Y-m-d H:i:s', time());
                $productProperties = $this->__getBasicPopupData($name, $options);
                break;
            case self::METHOD_CREATE_WEB_PUSH_CONSENT_FORM:
                $name = 'SSO ' . $options['title'] . date(' Y-m-d H:i:s', time());
                $productProperties = $this->__getWebPushConsentData($name, $options);
                break;
            default:
                throw new SalesManagoException('Unsupported data source type', 13);
        }

        $data = array_merge(
            $this->__getDefaultApiData($settings),
            $productProperties
        );

        $response = $this->request(self::METHOD_POST, $method, $data);
        $response['name'] = $name;
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
            Settings::TOKEN     => $settings->getToken(),
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
            Settings::TOKEN     => $settings->getToken(),
            Settings::CLIENT_ID => $settings->getClientId(),
            "properties"        => json_encode($properties)
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_SET_INTEGRATION_PROPERTIES, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $upsertDetails
     * @return array
     */
    public function exportContacts(Settings $settings, $upsertDetails)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'upsertDetails' => $upsertDetails,
        ));

        $response = $this->request(self::METHOD_POST, self::METHOD_BATCH_UPSERT, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $events
     * @return array
     */
    public function exportContactExtEvents(Settings $settings, $events)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'events' => $events,
        ));

        $response = $this->request(self::METHOD_POST, self::METHOD_BATCH_ADD_EXT_EVENT, $data);
        return $this->validateResponse($response);
    }

    /**
     * @param array $userData
     * @return array
     * @throws SalesManagoException
     */
    public function getAccountTypeWithContacts($userData)
    {
        $response = $this->request(self::METHOD_POST, self::METHOD_ACCOUNT_TYPE, $userData);
        return $this->validateResponse($response);
    }

    /**
     * @param Settings $settings
     * @param array $itemData
     * @return array
     * @throws SalesManagoException
     */
    public function itemAction(Settings $settings, $itemData)
    {
        $data = array_merge($this->__getDefaultApiData($settings), $itemData);

        $response = $this->request(self::METHOD_POST, self::METHOD_ITEM_ACTION, $data);
        return $this->validateResponse($response);
    }

    /**
     * @param array $userData
     * @return array
     * @throws SalesManagoException
     */
    public function consentFormCode($userData)
    {
        $response = $this->request(
            self::METHOD_POST,
            self::METHOD_CONSENT_FORM_CODE,
            array(
                'token'       => $userData['token'],
                'wizardIntId' => $userData['id']
            ));
        return $this->validateResponse($response);
    }

    /**
     * @param Settings $settings
     * @param $image
     * @return array
     * @throws SalesManagoException
     */
    public function uploadImage(Settings $settings, $image)
    {
        $this->setClient(
            $settings,
            array(
                'Content-Type' => 'multipart/form-data'
            )
        );

        $response = $this->requestCustomizable(
            self::METHOD_POST,
            self::METHOD_UPLOAD_IMAGE,
            array(
                'multipart' => array(
                    array(
                        'name'     => 'attachment',
                        'contents' => fopen($image['tmp_name'], 'r'),
                        'filename' => $image['name']
                    ),
                    array(
                        'name'     => Settings::CLIENT_ID,
                        'contents' => $settings->getClientId(),
                    ),
                    array(
                        'name'     => Settings::API_KEY,
                        'contents' => $settings->getApiKey(),
                    ),
                    array(
                        'name'     => Settings::SHA,
                        'contents' => $settings->getSha(),
                    ),
                    array(
                        'name'     => Settings::EMAIL,
                        'contents' => $settings->getOwner(),
                    )
                ),
            )
        );
        return $this->validateResponse($response);
    }

    /**
     * @param Settings $settings
     * @return array
     * @throws SalesManagoException
     */
    public function listUsersByClient(Settings $settings)
    {
        $data = $this->__getDefaultApiData($settings);
        $response = $this->request(self::METHOD_POST, self::METHOD_LIST_USERS, $data);
        return $this->validateResponse($response);
    }
}
