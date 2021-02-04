<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Configuration as Settings;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\User;

class SettingsModel
{
    const
        REQUEST_TIME = 'requestTime';

    /**
     * @var Settings
     */
    private $conf;

    public function __construct(Settings $conf)
    {
        $this->conf = $conf;
    }

    /**
     * @return array
     */
    public function getAuthorizationApiData()
    {
        return [
            Settings::CLIENT_ID => $this->conf->getClientId(),
            Settings::API_KEY   => $this->conf->getApiKey(),
            Settings::SHA       => $this->conf->getSha(),
            self::REQUEST_TIME  => time()
        ];
    }

    /**
     * @return array
     */
    public function getAuthorizationApiDataWithOwner()
    {
        return array_merge(
            $this->getAuthorizationApiData(),
            [Configuration::OWNER => $this->conf->getOwner()]
        );
    }

    /**
     * @param $accountAuthorizeResponse
     * @param User $User
     * @return Configuration
     */
    public function setConfAfterAccountAuthorization($accountAuthorizeResponse, User $User)
    {
        $this->conf
            ->setOwner($User->getEmail())
            ->setToken($accountAuthorizeResponse[Configuration::TOKEN])
            ->setEndpoint($accountAuthorizeResponse['endpoint']);

        return $this->conf;
    }

    /**
     * @param array $responseIntegration
     * @return Configuration
     */
    public function setConfAfterIntegration($responseIntegration = []) {
        $this->conf
            ->setSha($responseIntegration['sha1'])
            ->setClientId($responseIntegration['shortId']);

        return $this->conf;
    }
}