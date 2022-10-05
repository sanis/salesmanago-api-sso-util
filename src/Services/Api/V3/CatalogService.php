<?php

namespace SALESmanago\Services\Api\V3;

use SALESmanago\Entity\Api\V3\CatalogEntity;
use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Entity\Api\V3\ConfigurationInterface;
use SALESmanago\Entity\cUrlClientConfiguration;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Exception\Exception;

class CatalogService
{
    const
        REQUEST_METHOD_POST = 'POST',
        REQUEST_METHOD_GET  = 'GET',
        API_METHOD_LIST     = '/v3/product/catalogList',
        API_METHOD_CREATE   = '/v3/product/catalogUpsert';

    /**
     * @var RequestService
     */
    protected $RequestService;

    /**
     * @throws Exception
     */
    public function __construct(ConfigurationInterface $ConfigurationV3)
    {
        $cUrlClientConfiguration = new cUrlClientConfiguration(
            [
                'host' => $ConfigurationV3->getApiV3Endpoint(),
                'headers' => [
                    'Api-KEY' => $ConfigurationV3->getApiV3Key()
                ]
            ]
        );

        $this->RequestService = new RequestService($cUrlClientConfiguration);
    }

    /**
     * @return array
     * @throws ApiV3Exception|Exception
     */
    public function getCatalogs()
    {
        $response = $this->RequestService->request(
            self::REQUEST_METHOD_GET,
            self::API_METHOD_LIST
        );

        $catalogs = [];

        if (!empty($response['catalogs'])) {
            foreach ($response['catalogs'] as $catalog) {
                $catalogs[] = new CatalogEntity($catalog);
            }
        }

        if (!empty($response['messages']) && !empty($response['reasonCode'])) {
            $messages = is_array($response['messages'])
                ? implode(', ', $response['messages'])
                : $response['messages'];

            throw new ApiV3Exception($messages, $response['reasonCode']);
        }

        return $catalogs;
    }

//    /**
//     * @param CatalogEntityInterface $Catalog
//     * @return array|null
//     * @throws ApiV3Exception
//     */
//    public function createCatalog(CatalogEntityInterface $Catalog)
//    {
//        return $this->RequestService->request(
//            self::REQUEST_METHOD_POST,
//            self::API_METHOD_CREATE,
//            json_encode($Catalog)
//        );
//    }
}