<?php

namespace SALESmanago\Services\Api\V3;

use \Exception;
use SALESmanago\Entity\RequestClientConfigurationInterface;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Helper\ConnectionClients\cURLClient;

class RequestService
{
    private $connectionClient;

    public function __construct(
        RequestClientConfigurationInterface $cUrlClientConfiguration
    ) {
        $this->connectionClient = new cURLClient();
        $this->connectionClient->setConfiguration($cUrlClientConfiguration);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @throws ApiV3Exception
     */
    final public function request($method, $uri, $data = null)
    {
        try {
            $this->connectionClient
                ->setType($method)
                ->setEndpoint($uri);

            $this->connectionClient->request($data);
            return $this->connectionClient->responseJsonDecode();
        } catch (Exception $e) {
            throw new ApiV3Exception($e->getMessage(), $e->getCode());
        }
    }
}