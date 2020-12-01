<?php

namespace bhr\Salesmanago\Services;

use GuzzleHttp\Client as GuzzleClient;
use bhr\Salesmanago\Entity\Configuration;

class GuzzleClientAdapter
{

    /**
     * Checks const of GuzzleClient
     * @return array
     * @throws \ReflectionException
     */
    public function getConstants()
    {
        $object = new \ReflectionClass(GuzzleClient::class);
        return $object->getConstants();
    }

    /**
     * Checking GuzzleClient version.
     * In version 7+ const VERSION doesn't exist.
     * Instead const MAJOR_VERSION.
     * @param Configuration $settings
     * @param $headers
     * @return GuzzleClient
     */
    public function setClient(Configuration $settings, $headers)
    {
        $arrayOfConstant = $this->getConstants();

        if (array_key_exists('VERSION', $arrayOfConstant)) {

        if (version_compare(GuzzleClient::VERSION, '6.0.0', '<')) {
            $client = new GuzzleClient([
                'base_url' => $settings->getRequestEndpoint(),
                'defaults' => array(
                    'verify' => false,
                    'timeout' => 45.0,
                    'headers' => $headers
                )
            ]);
            return $client;

        } elseif (version_compare(GuzzleClient::VERSION, '6.0.0', '>')) {
            $client = new GuzzleClient([
                'base_uri' => $settings->getRequestEndpoint(),
                'verify' => false,
                'timeout' => 45.0,
                'defaults' => [
                    'headers' => $headers,
                ]
            ]);
            return $client;

            }
        } else {
            $client = new GuzzleClient([
                'base_uri' => $settings->getRequestEndpoint(),
                'verify' => false,
                'timeout' => 45.0,
                'defaults' => [
                    'headers' => $headers,
                ]
            ]);
            return $client;
        }
    }
}
