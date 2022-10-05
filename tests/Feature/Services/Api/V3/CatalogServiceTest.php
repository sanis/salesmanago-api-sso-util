<?php

namespace Tests\Feature\Services\Api\V3;

use Faker;
use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\Api\V3\CatalogService;
use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use SALESmanago\Entity\Api\V3\CatalogEntity;

class CatalogServiceTest extends AbstractBasicV3ServiceTest
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
        $this->createConfigurationEntity();

        $CatalogService = new CatalogService(
            ConfigurationEntity::getInstance()//created with $this->createConfigurationEntity()
        );

        $Catalog = $this->createCatalogEntityWithDummyData();

        $Response = $CatalogService->createCatalog($Catalog);



        var_dump($Response);
        die;
    }

    /**
     * @throws Exception
     */
    protected function createCatalogEntityWithDummyData()
    {
        $faker = Faker\Factory::create();

        return new CatalogEntity(
            [
                //"catalogId"    => '',
                "catalogName"   => 'Catalog ' . $faker->word,
                //"setAsDefault" => '',
                "currency"     => $faker->currencyCode,
                "location"     => $faker->word
            ]
        );
    }
}