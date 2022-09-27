<?php

namespace SALESmanago\Services\ApiV3;

use SALESmanago\Entity\ApiV3ConfigurationInterface;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Exception\Exception;
use SALESmanago\Helper\ConnectionClients\cURLClient;
use SALESmanago\Services\RequestService as BasicRequestService;

class RequestService extends BasicRequestService
{
    /**
     * @var cURLClient
     */
    private $connClient;

    /**
     * RequestService constructor.
     *
     * @param ApiV3ConfigurationInterface $conf
     * @throws Exception
     */
    public function __construct(ApiV3ConfigurationInterface $conf)
    {
        parent::__construct($conf);

        if (!empty($conf->getApiKeyV3())) {
            try {
                $this->connClient = new cURLClient();

                $RequestClientConf = $conf->getRequestClientConf();
                $RequestClientConf->setHost($conf->getApiV3Endpoint());
                $RequestClientConf->setHeaders(['API_KEY' => $conf->getApiKeyV3()]);

                $conf->setRequestClientConf($RequestClientConf);

                $this->connClient->setConfiguration($conf->getRequestClientConf());
            } catch (\Exception $e) {
                throw new Exception('Error while setting Connection Client: ' . $e->getMessage(), 401);
            }
        } else {
            throw new Exception('Missed api key v3: ', 401);
        }
    }
}