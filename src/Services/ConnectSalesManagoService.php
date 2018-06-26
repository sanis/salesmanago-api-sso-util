<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoException;


class ConnectSalesManagoService extends AbstractClient implements ApiMethodInterface
{
    const EVENT_TYPE_CART = "CART",
          EVENT_TYPE_PURCHASE = "PURCHASE";

    use EventTypeTrait;

    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $userEmail
     * @return array
     */
    public function getContactByEmail(Settings $settings, $userEmail)
    {
        $data = array_merge(
            $this->__getDefaultApiData($settings),
            array(
                'email' => array($userEmail)
            )
        );

        $response = $this->request(self::METHOD_POST, self::METHOD_STATUS, $data);

        if (is_array($response)
            && array_key_exists('success', $response)
            && count($response['contacts']) === 1
        ) {
            $user = array_pop($response['contacts']);
            return array(
                "success"   => true,
                "contactId" => $user['contactId'],
            );
        } else {
            return $this->contactUpsert(
                $settings,
                array(
                    "email" => $userEmail
                ),
                array(
                    "forceOptIn"  => false,
                    "forceOptOut" => true,
                )
            );
        }
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $contactId
     * @return array
     */
    public function getContactById(Settings $settings, $contactId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'id' => array($contactId),
        ));

        $response = $this->request(self::METHOD_POST, self::METHOD_STATUS_BY_ID, $data);
        return $this->validateResponse($response);
    }

    public function checkForceOptions($options)
    {
        if (isset($options['force']) && $options['force'] == true) {
            return false;
        }

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
        $data = $this->__getDefaultApiData($settings);

        if ($this->checkForceOptions($options)) {

            $userData = array_merge($data, array('email' => array($user['email'])));
            $response = $this->request(self::METHOD_POST, self::METHOD_STATUS, $userData);

            if (is_array($response)
                && array_key_exists('success', $response)
                && count($response['contacts']) === 1
            ) {
                $contactData = array_pop($response['contacts']);
                if ($contactData['optedOut'] == false) {
                    $options['forceOptIn']  = true;
                    $options['forceOptOut'] = false;
                }
            } else {
                $options['forceOptIn']  = false;
                $options['forceOptOut'] = true;
            }
        }

        $data = array_merge($data, array('contact' => $this->__getContactData($user)));

        $tag = array(
            'tags'       => array(),
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
                } elseif ($key == 'force') {
                    continue;
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

        $response = $this->request(self::METHOD_POST, self::METHOD_UPSERT, $this->filterData($data));
        return $this->validateCustomResponse($response, array(array_key_exists('contactId', $response)));
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

        $response = $this->request(self::METHOD_POST, self::METHOD_DELETE, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $user
     * @return array
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

        $response = $this->request(self::METHOD_POST, self::METHOD_ADD_NOTE, $data);
        return $this->validateResponse($response);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param string $type
     * @param array $product
     * @param array $user
     * @param string $eventId
     * @return array
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

        $response = $this->request(self::METHOD_POST, $method, $this->filterData($data));
        return $this->validateCustomResponse($response, array(array_key_exists('eventId', $response)));
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
            'date'                => 1000 * time(),
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