<?php

namespace SALESmanago\Services\Api\V3;

use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Entity\Api\V3\ConfigurationInterface;
use SALESmanago\Entity\cUrlClientConfiguration;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\Api\V3\ProductsModel;
use SALESmanago\Model\Collections\Api\V3\ProductsCollectionInterface;
use SALESmanago\Exception\ApiV3Exception;

class ProductService
{
    const
        REQUEST_METHOD_POST = 'POST',
        REQUEST_METHOD_GET  = 'GET',
        API_METHOD_UPSERT   = 'v3/product/upsert';

    /**
     * @var RequestService
     */
    protected $RequestService;

    /**
     * @var ProductsModel
     */
    protected $ProductsModel;

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
        $this->ProductsModel  = new ProductsModel();
    }

    /**
     * @param CatalogEntityInterface $Catalog
     * @param ProductsCollectionInterface $ProductsCollection
     * @return array
     * @throws ApiV3Exception
     */
    public function upsertProducts(
        CatalogEntityInterface $Catalog,
        ProductsCollectionInterface $ProductsCollection
    ) {

        $data = $this->ProductsModel->getProductsToUpsert($Catalog, $ProductsCollection);

        return $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD_UPSERT,
            json_encode($data)
        );
    }
}