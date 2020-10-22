<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Settings;

class SettingsModel
{
    const
        REQUEST_TIME = 'requestTime';

    /**
     * @var Settings
     */
    private $Settings;

    public function __construct(Settings $Settings)
    {
        $this->Settings = $Settings;
    }

    public function getAuthorizationApiData()
    {
        return [
            Settings::CLIENT_ID => $this->Settings->getClientId(),
            Settings::API_KEY   => $this->Settings->getApiKey(),
            Settings::SHA       => $this->Settings->getSha(),
            Settings::OWNER     => $this->Settings->getOwner(),
            self::REQUEST_TIME  => time()
        ];
    }
}