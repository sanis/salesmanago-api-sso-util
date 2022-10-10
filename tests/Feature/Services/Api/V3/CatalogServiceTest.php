<?php

namespace Tests\Feature\Services\Api\V3;

use Faker;
use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\Api\V3\CatalogService;
use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use SALESmanago\Entity\Api\V3\CatalogEntity;

class CatalogServiceTest extends TestAbstractBasicV3Service
{
    /**
     * Test get catalog success
     *
     * @return void
     * @throws Exception
     * @throws ApiV3Exception
     */
    public function testGetCatalogsSuccess()
    {
        //create configuration for request service
        $this->createConfigurationEntity();

        //create & setup CatalogService:
        $CatalogService = new CatalogService(
            ConfigurationEntity::getInstance()//created with $this->createConfigurationEntity()
        );

        //get catalogs:
        $catalogs = $CatalogService->getCatalogs();

        //checked if result is an array:
        $this->assertIsArray($catalogs);

        //checked if result implements interface
        foreach ($catalogs as $catalog) {
            $this->assertInstanceOf(CatalogEntityInterface::class, $catalog);
        }
    }

    /**
     * Test create catalog success
     *
     * @return void
     * @throws ApiV3Exception
     * @throws Exception
     */
    public function testCreateCatalogSuccess()
    {
        //create configuration for request service
        $this->createConfigurationEntity();

        //create & setup CatalogService:
        $CatalogService = new CatalogService(
            ConfigurationEntity::getInstance()//created with $this->createConfigurationEntity()
        );

        //create entity
        $Catalog = $this->createCatalogEntityWithDummyData();

        //upsert catalog
        $response = $CatalogService->createCatalog($Catalog);

        $this->assertIsArray($response);
        $this->assertTrue(!empty($response['requestId']));
        $this->assertTrue(!empty($response['catalogId']));
    }

    /**
     * @throws Exception
     */
    protected function createCatalogEntityWithDummyData()
    {
        $this->faker = Faker\Factory::create();

        return new CatalogEntity(
            [
                "catalogName"  => 'Catalog ' . $this->faker->word,
                "currency"     => $this->faker->currencyCode,
                "location"     => 'time'.time()
            ]
        );
    }
}