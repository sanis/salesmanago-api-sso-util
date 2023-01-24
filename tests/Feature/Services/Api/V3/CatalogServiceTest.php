<?php

namespace Tests\Feature\Services\Api\V3;

use Generator;
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
     * Checking creation of catalog with different create catalog entity methods
     *
     * @dataProvider provideCatalogEntityData
     * @return void
     * @throws ApiV3Exception
     */
    public function testCreateCatalogSuccess(CatalogEntityInterface $Catalog)
    {
        //create configuration for request service
        $this->createConfigurationEntity();

        //create & setup CatalogService:
        $CatalogService = new CatalogService(
            ConfigurationEntity::getInstance()//created with $this->createConfigurationEntity()
        );

        //upsert catalog
        $response = $CatalogService->createCatalog($Catalog);

        //check
        $this->assertIsArray($response);
        $this->assertTrue(!empty($response['requestId']));
        $this->assertTrue(!empty($response['catalogId']));
    }

    /**
     * Provide data for testCreateCatalogSuccess
     *
     * @return Generator
     * @throws Exception
     */
    public function provideCatalogEntityData()
    {
        yield [$this->createCatalogEntityWithDummyData()];
        yield [$this->createCatalogEntityThroughtStandardizedMethodsWithDummyData()];
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

    /**
     * @throws Exception
     */
    protected function createCatalogEntityThroughtStandardizedMethodsWithDummyData()
    {
        $this->faker = Faker\Factory::create();

        return new CatalogEntity(
            [
                "name"         => 'Catalog ' . $this->faker->word,
                "currency"     => $this->faker->currencyCode,
                "location"     => 'time'.time()
            ]
        );
    }
}