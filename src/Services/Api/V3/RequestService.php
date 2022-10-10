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
            $response = $this->connectionClient->responseJsonDecode();

            if (!empty($response['messages']) && !empty($response['reasonCode'])) {
                $messages = is_array($response['messages'])
                    ? implode(', ', $response['messages'])
                    : $response['messages'];

                throw new ApiV3Exception($messages, $response['reasonCode']);
            }

            if (isset($response['problems'])) {
                $messages = [];
                foreach ($response['problems'] as $problem) {
                    $messages[] = 'reasonCode: ' . $problem['reasonCode'] . ' - message: ' . $problem['message'];
                }

                $messages = implode('; ', $messages);
                throw new ApiV3Exception($messages, $response['reasonCode']);
            }

            return $response;

        } catch (Exception $e) {
            throw new ApiV3Exception($e->getMessage(), $e->getCode());
        }
    }
}