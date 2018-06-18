<?php

namespace SALESmanago\Services;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Entity\Settings;


class CreateAccountService extends AbstractClient implements CreateAccountInterface
{
    public function __construct(Settings $settings)
    {
        $this->setClient($settings);
    }

    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @param array $user
     * @param array $modulesId
     * @return array
     */
    public function createAccount(Settings $settings, $user, $modulesId)
    {
        $data = array_merge($this->__getDefaultApiData($settings), array(
            'email' => $user['email'],
            'password' => $user['password'],
            'lang' => $user['lang'],
            'items' => json_encode($this->__getModulesData($modulesId))
        ));

        if (isset($user['tags'])) {
            $data['tags'] = $user['tags'];
        }

        $response = $this->request(self::METHOD_POST, self::METHOD_CREATE_ACCOUNT, $data);
        return $this->validateResponse($response);
    }


    /**
     * @throws SalesManagoException
     * @var Settings $settings
     * @return array
     */
    public function contactToSupport(Settings $settings)
    {
        $data = $this->__getDefaultApiData($settings);

        if (count($settings->getTags()) > 0) {
            $tag['tags'] = $settings->getTags();
        }

        if (count($settings->getRemoveTags()) > 0) {
            $tag['removeTags'] = $settings->getRemoveTags();
        }

        if (count($settings->getProperties()) > 0) {
            $tag['properties'] = $settings->getProperties();
        }

        $response = $this->request(self::METHOD_POST, self::METHOD_CONTACT_SUPPORT, $data);
        return $this->validateCustomResponse($response, array(array_key_exists('contactId', $response)));
    }
}