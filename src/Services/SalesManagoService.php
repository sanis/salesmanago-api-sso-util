<?php

namespace SALESmanago\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;


class SalesManagoService
{
    const METHOD_UPSERT = "/api/contact/upsert",
          METHOD_DELETE = "/api/contact/delete",
          METHOD_BATCH_UPSERT = "/api/contact/batchupsert",
          METHOD_ADD_EXT_EVENT = "/api/contact/addContactExtEvent",
          METHOD_BATCH_ADD_EXT_EVENT = "/api/contact/batchAddContactExtEvent",
          METHOD_UPDATE_EXT_EVENT = "/api/contact/updateContactExtEvent",
          METHOD_ACCOUNT_ITEMS = "/api/contact/updateContactExtEvent",
          METHOD_ADD_NOTE = "/api/contact/addNote",
          METHOD_STATUS = "/api/contact/basic",
          METHOD_STATUS_BY_ID = "/api/contact/basicById",

          EVENT_TYPE_CART = "CART",
          EVENT_TYPE_PURCHASE = "PURCHASE";

    /** @var GuzzleClient $guzzle */
    protected $guzzle;

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
                'timeout' => 15.0,
                'defaults' => [
                    'headers' => [
                        'Accept' => 'application/json, application/json',
                        'Content-Type' => 'application/json;charset=UTF-8',
                    ],
                ],
            ]);
        }
        return $this->guzzle;
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $userEmail
     * @return array
     */
    public function getContactId(Settings $settings, $userEmail)
    {
        $data = array_merge(
            $this->__getDefaultApiData($settings),
            array(
                'email' => array($userEmail)
            )
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_STATUS, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && count($response['contacts']) === 1
            ) {
                $user = array_pop($response['contacts']);
                return array(
                    "success" => true,
                    "contactId" => $user['contactId'],
                );
            } else {
                return $this->contactUpsert(
                    $settings,
                    array(
                        "email" => $userEmail
                    ),
                    array(
                        "forceOptIn" => false,
                        "forceOptOut" => true,
                    )
                );
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
     * @param string $userEmail
     * @return array
     */
    public function contactStatusById(Settings $settings, $userEmail)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'email' => array($userEmail),
        ));

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_STATUS_BY_ID, array(
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
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        }
    }

    public function checkForceOptions($options)
    {
        return !(isset($options['forceOptIn']) && $options['forceOptIn'] == true) ||
            (isset($options['forceOptOut']) && $options['forceOptOut'] == true);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $user
     * @param array $options
     * @param array $properties
     * @return array
     */
    public function contactUpsert(Settings $settings, $user, $options = array(), $properties = array())
    {
        try {
            $data = $this->__getDefaultApiData($settings);

            $guzzle = $this->getGuzzleClient($settings);

            if ($this->checkForceOptions($options)) {

                $userData = array_merge($data, array('email' => array($user['email'])));

                $guzzleResponse = $guzzle->request('POST', self::METHOD_STATUS, array(
                    'json' => $userData,
                ));

                $rawResponse = $guzzleResponse->getBody()->getContents();

                $response = json_decode($rawResponse, true);

                if (is_array($response)
                    && array_key_exists('success', $response)
                    && count($response['contacts']) === 1
                ) {
                    $user = array_pop($response['contacts']);
                    if ($user['optedOut'] == false){
                        $options['forceOptIn'] = true;
                        $options['forceOptOut'] = false;
                    }
                } else {
                    $options['forceOptIn'] = false;
                    $options['forceOptOut'] = true;
                }
            }

            $data = array_merge($data, array('contact' => $this->__getContactData($user)));

            $tag = array(
                'tags' => array(),
                'removeTags' => array(),
            );

            if (count($settings->getTags()) > 0) {
                $tag['tags'] = $settings->getTags();
            }

            if (count($settings->getRemoveTags()) > 0) {
                $tag['removeTags'] = $settings->getRemoveTags();
            }

            if (!empty($options)) {
                foreach ($options as $key => $value) {
                    if ($key == 'tags') {
                        $tag['tags'] = array_merge($tag['tags'], $this->__prepareTags($value));
                    } elseif ($key == 'removeTags') {
                        $tag['removeTags'] = array_merge($tag['removeTags'], $this->__prepareTags($value));
                    } else {
                        $data[$key] = $value;
                    }
                }
            }

            if (!empty($tag['tags'])) {
                $data['tags'] = $tag['tags'];
            }

            if (!empty($tag['removeTags'])) {
                $data['removeTags'] = $tag['removeTags'];
            }

            if (!empty($properties)) {
                foreach ($properties as $key => $value) {
                    $data['properties'][$key] = $value;
                }
            }


            $guzzleResponse = $guzzle->request('POST', self::METHOD_UPSERT, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && array_key_exists('contactId', $response)
                && $response['success'] == true
            ) {
                return $response;
            } else {
                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
     * @param string $userEmail
     * @return array
     */
    public function contactDelete(Settings $settings, $userEmail = '')
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'email' => $userEmail,
        ));

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_DELETE, array(
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
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
     * @param array $user
     * @return string
     */
    public function contactAddNote(Settings $settings, $user)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'priv' => $user['priv'],
            'note' => $user['note'],
        ));

        if (!empty($user['email'])) {
            $data['email'] = $user['email'];
        } elseif (!empty($user['contactId'])) {
            $data['contactId'] = $user['contactId'];
        }

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_ADD_NOTE, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && $response['success'] == true
            ) {
                return implode(' | ', $response['message']);
            } else {
                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
     * @param string $type
     * @param array $product
     * @param array $user
     * @param string $eventId
     * @return string
     */
    public function contactExtEvent(Settings $settings, $type, $product, $user, $eventId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'contactEvent' => $this->__getContactEventData($product, $type, $eventId),
        ));

        if (!empty($user['email'])) {
            $data['email'] = $user['email'];
        } else {
            $data['contactId'] = $user['contactId'];
        }

        if (!empty($eventId)) {
            $method = self::METHOD_UPDATE_EXT_EVENT;
        } else {
            $method = self::METHOD_ADD_EXT_EVENT;
        }

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', $method, array(
                'json' => $data,
            ));

            $rawResponse = $guzzleResponse->getBody()->getContents();

            $response = json_decode($rawResponse, true);

            if (is_array($response)
                && array_key_exists('success', $response)
                && array_key_exists('eventId', $response)
                && $response['success'] == true
            ) {
                return $response;
            } else {
                throw SalesManagoError::handleError($response['message'], $guzzleResponse->getStatusCode());
            }
        } catch (ConnectException $e) {
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
     * @param array $user
     * @param array $options
     * @param array $properties
     * @return array
     */
    public function prepareContactsDetails(
        Settings $settings,
        $user = array(),
        $options = array(),
        $properties = array()
    )
    {
        $data = array(
            'contact' => $this->__getContactData($user),
        );

        $tag = array(
            'tags' => array(),
            'removeTags' => array(),
        );

        if (count($settings->getTags()) > 0) {
            $tag['tags'] = $settings->getTags();
        }

        if (count($settings->getRemoveTags()) > 0) {
            $tag['removeTags'] = $settings->getRemoveTags();
        }

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                if ($key == 'tags') {
                    $tag['tags'] = array_merge($tag['tags'], $this->__prepareTags($value));
                } elseif ($key == 'removeTags') {
                    $tag['removeTags'] = array_merge($tag['removeTags'], $this->__prepareTags($value));
                } else {
                    $data[$key] = $value;
                }
            }
        }

        if (!empty($tag['tags'])) {
            $data['tags'] = $tag['tags'];
        }

        if (!empty($tag['removeTags'])) {
            $data['removeTags'] = $tag['removeTags'];
        }

        if (!empty($properties)) {
            foreach ($properties as $key => $value) {
                $data['properties'][$key] = $value;
            }
        }

        return $data;
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

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_BATCH_UPSERT, array(
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
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        }
    }

    /**
     * @param string $type
     * @param array $product
     * @param array $user
     * @return array
     */
    public function prepareContactEvents($type, $product = array(), $user = array())
    {
        $data = array(
            'contactEvent' => $this->__getContactEventData($product, $type, ""),
        );

        if (!empty($user['email'])) {
            $data['email'] = $user['email'];
        } elseif (!empty($user['contactId'])) {
            $data['contactId'] = $user['contactId'];
        }

        return $data;
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

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_BATCH_ADD_EXT_EVENT, array(
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
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
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
    public function accountItems(Settings $settings)
    {
        $data = array(
            'token' => $settings->getToken(),
            'apiKey' => $settings->getApiKey(),
        );

        try {
            $guzzle = $this->getGuzzleClient($settings);

            $guzzleResponse = $guzzle->request('POST', self::METHOD_ACCOUNT_ITEMS, array(
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
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        }
    }

    protected function __getDefaultApiData(Settings $settings)
    {
        $data = array(
            'async' => false,
            'clientId' => $settings->getClientId(),
            'apiKey' => $settings->getApiKey(),
            'requestTime' => time(),
            'sha' => $settings->getSha(),
            'owner' => $settings->getOwner(),
        );
        return $data;
    }

    protected function __getContactData($user)
    {
        if (!empty($user)) {
            $contact = array();
            foreach ($user as $key => $value) {
                if (in_array($key, array("streetAddress", "zipCode", "city", "country"))) {
                    $contact["address"][$key] = $value;
                } else {
                    $contact[$key] = $value;
                }
            }
            return $contact;
        }
    }

    protected function __getContactEventData($product, $type, $eventId)
    {
        $contactEvent = array(
            'date' => 1000 * time(),
            'contactExtEventType' => $type,
        );

        $contactEvent = array_merge(
            $contactEvent,
            $product
        );

        if (!empty($eventId)) {
            $contactEvent['eventId'] = $eventId;
        }

        return $contactEvent;
    }

    protected function __prepareTags($tags)
    {
        $tags = explode(',', $tags);
        foreach ($tags as $key => $tag) {
            $tags[$key] = strtoupper(trim($tag));
        }
        return $tags;
    }
}