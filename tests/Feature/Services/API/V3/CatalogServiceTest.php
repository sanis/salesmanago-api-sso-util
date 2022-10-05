<?php

namespace Tests\Feature\Services\API\V3;

use Faker;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\API\V3\CatalogService;
use SALESmanago\Entity\API\V3\ConfigurationEntity;
use SALESmanago\Entity\API\V3\CatalogEntity;

class CatalogServiceTest extends AbstractBasicV3ServiceTest
{
    /**
     * Test get catalog success
     *
     * @return void
     */
    public function testGetCatalogsSuccess()
    {
        $this->createConfigurationEntity();

        $CatalogService = new CatalogService(
            ConfigurationEntity::getInstance()//created with $this->createConfigurationEntity()
        );

        $catalogs = $CatalogService->getCatalogs();

        var_dump($catalogs);
        die;
    }

    /**
     * Test create catalog success
     *
     * @return void
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
                "name"         => 'Catalog ' . $faker->word,
                //"setAsDefault" => '',
                "currency"     => $faker->currencyCode,
                "location"     => $faker->uuid
            ]
        );
    }
}