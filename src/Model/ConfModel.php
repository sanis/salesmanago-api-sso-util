<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\User;

class ConfModel
{
    const
        REQUEST_TIME = 'requestTime';

    /**
     * @var Configuration
     */
    private $conf;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
    }

    /**
     * @return array
     */
    public function getAuthorizationApiData()
    {
        return [
            Configuration::CLIENT_ID => $this->conf->getClientId(),
            Configuration::API_KEY   => $this->conf->getApiKey(),
            Configuration::SHA       => $this->conf->getSha(),
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