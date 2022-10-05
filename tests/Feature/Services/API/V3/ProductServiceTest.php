<?php

namespace Tests\Feature\Services\API\V3;

use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use SALESmanago\Entity\Api\V3\Product\ProductEntityInterface;
use SALESmanago\Model\Collections\Api\V3\ProductsCollection;
use SALESmanago\Services\Api\V3\CatalogService;

class ProductServiceTest extends AbstractBasicV3ServiceTest
{
    public function testUpsertProducts()
    {
        //todo
    }

    protected function getCatalogToUpsertProducts()
    {
        //create ConfigurationEntity singleton
        $this->createConfigurationEntity();

        //create catalog service to get data
        $CatalogService = new CatalogService(ConfigurationEntity::getInstance());

        $Catalogs = $CatalogService->getCatalogs();

        //todo choose catalog

        return $Catalogs;
    }

    /**
     * @return ProductEntityInterface
     */
    protected function createDummyProduct()
    {

    }

    /**
     * @return ProductsCollection
     */
    protected function createProductCollection()
    {

    }
}