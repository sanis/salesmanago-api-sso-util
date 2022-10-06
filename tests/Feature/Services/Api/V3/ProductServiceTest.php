<?php

namespace Tests\Feature\Services\Api\V3;

use Faker;
use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use SALESmanago\Entity\Api\V3\Product\ProductEntity;
use SALESmanago\Entity\Api\V3\Product\ProductEntityInterface;
use SALESmanago\Entity\Api\V3\Product\SystemDetailsEntity;
use SALESmanago\Model\Collections\Api\V3\ProductsCollection;
use SALESmanago\Services\Api\V3\CatalogService;
use SALESmanago\Entity\Api\V3\Product\CustomDetailsEntity;

class ProductServiceTest extends TestAbstractBasicV3Service
{
    public function testUpsertProducts()
    {
        $Product = $this->createProduct();

        var_dump($Product);
        die;
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
    protected function createProduct()
    {
        $faker = Faker\Factory::create();
        $Product = new ProductEntity();

        //create system details:
        $SystemDetails = $this->createSystemDetails();

        //create custom details:
        $CustomDetails = $this->createCustomDetails();

        $Product
            ->setActive(true)
            ->setAvailable(true)
            ->setCategories($faker->words($faker->numberBetween(1, 30)))
            ->setCategoryExternalId($faker->uuid)
            ->setCustomDetails($CustomDetails)
            ->setDescription($faker->words())
            ->setDiscountPrice($faker->randomNumber())
            ->setImageUrls($this->createImagesUrls())
            ->setMainCategory($faker->words(1)[0])
            ->setName($faker->words(1)[0])
            ->setPrice($faker->randomNumber())
            ->setUnitPrice($faker->randomNumber())
            ->setSystemDetails($SystemDetails);

        return $Product;
    }

    /**
     * Creates CustomDetails objects
     * @return CustomDetailsEntity
     */
    protected function createCustomDetails()
    {
        $faker = Faker\Factory::create();

        $CustomDetails = new CustomDetailsEntity();
        $numberOfDetails = $faker->numberBetween(1, 5);

        while ($numberOfDetails) {
            $CustomDetails->set($faker->words(), $numberOfDetails);
            --$numberOfDetails;
        }

        return $CustomDetails;
    }

    protected function createSystemDetails()
    {
        $faker = Faker\Factory::create();

        $SystemDetails = new SystemDetailsEntity();
        $SystemDetails
            ->setBrand($faker->words(1))
            ->setManufacturer($faker->words(1))
            ->setPopularity($faker->numberBetween(1, 100))
            ->setGender($faker->randomKey(['-1', '0', '1', '1', '2', '4']))
            ->setSeason($faker->randomKey(['spring', 'summer', 'autumn', 'winter']))
            ->setColor($faker->colorName)
            ->setBestseller($faker->boolean())
            ->setNewProduct($faker->boolean());

        return $SystemDetails;
    }

    /**
     * Creates images url
     * @return array
     */
    protected function createImagesUrls()
    {
        $faker = Faker\Factory::create();

        $imgs = [];
        $cImages = $faker->numberBetween(1, 5);

        for ($i=0; $i < $cImages; $i++) {
            $imgs[] = $faker->imageUrl();
        }

        return $imgs;
    }

    /**
     * @return ProductsCollection
     */
    protected function createProductCollection()
    {

    }
}