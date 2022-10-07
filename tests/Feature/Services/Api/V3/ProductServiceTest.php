<?php

namespace Tests\Feature\Services\Api\V3;

use Faker;
use SALESmanago\Entity\Api\V3\CatalogEntity;
use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use SALESmanago\Entity\Api\V3\Product\ProductEntity;
use SALESmanago\Entity\Api\V3\Product\ProductEntityInterface;
use SALESmanago\Entity\Api\V3\Product\SystemDetailsEntity;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\Api\V3\ProductsModel;
use SALESmanago\Model\Collections\Api\V3\ProductsCollection;
use SALESmanago\Services\Api\V3\CatalogService;
use SALESmanago\Entity\Api\V3\Product\CustomDetailsEntity;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Services\Api\V3\ProductService;

class ProductServiceTest extends TestAbstractBasicV3Service
{
    /**
     * @throws Exception
     * @throws ApiV3Exception
     */
    public function testUpsertProductsSuccess()
    {
        $countProds = $this->faker->numberBetween(1, 100);//up to 100 products per request

        //create products collection
        $ProductsCollection = $this->createProductsCollection($countProds);

        //get or create catalog
        $Catalog = $this->getCatalogToUpsertProducts();

        $this->createConfigurationEntity();
        $ProductService = new ProductService(ConfigurationEntity::getInstance());

        $response = $ProductService->upsertProducts($Catalog, $ProductsCollection);

        $this->assertArrayNotHasKey('problems', $response);
        $this->assertArrayHasKey('requestId', $response);
        $this->assertArrayHasKey('productIds', $response);
    }

    /**
     * @return mixed|CatalogEntity|CatalogEntityInterface
     * @throws ApiV3Exception
     * @throws Exception
     */
    protected function getCatalogToUpsertProducts()
    {
        //create ConfigurationEntity singleton
        $this->createConfigurationEntity();

        //create catalog service to get data
        $CatalogService = new CatalogService(ConfigurationEntity::getInstance());

        $catalogsArr = $CatalogService->getCatalogs();

        if (!empty($catalogsArr)) {
           return $catalogsArr[array_rand($catalogsArr, 1)];
        }

        return $this->createCatalog($CatalogService);
    }

    /**
     * @param CatalogService $CatalogService
     * @return CatalogEntityInterface
     * @throws ApiV3Exception
     */
    protected function createCatalog(CatalogService $CatalogService)
    {
        $Catalog = new CatalogEntity();

        $Catalog
            ->setCatalogName('Catalog ' . $this->faker->word)
            ->setCurrency($this->faker->currencyCode)
            ->setLocation('time'.time())
            ->setSetAsDefault($this->faker->boolean());

        $Catalog->setCatalogId($CatalogService->createCatalog($Catalog)['catalogId']);

        return $Catalog;
    }

    /**
     * Generates products
     * @param int $numberOfProductsInProducts
     * @return ProductsCollection
     */
    protected function createProductsCollection($numberOfProductsInProducts = 1)
    {
        $ProductsCollection = new ProductsCollection();

        while ($numberOfProductsInProducts) {
            $ProductsCollection->addItem($this->createProduct());
            --$numberOfProductsInProducts;
        }

        return $ProductsCollection;
    }

    /**
     * @return ProductEntityInterface
     */
    protected function createProduct()
    {
        $this->faker = Faker\Factory::create();
        $Product = new ProductEntity();

        //create system details:
        $SystemDetails = $this->createSystemDetails();

        //create custom details:
        $CustomDetails = $this->createCustomDetails();

        $productId = $this->faker->uuid;
        $productId = (count_chars($productId) > 32)
            ? substr($productId, 0, 31)
            : $productId;

        $Product
            ->setProductId($productId)
            ->setActive(true)
            ->setAvailable(true)
            ->setCategories($this->faker->words($this->faker->numberBetween(1, 5)))
            ->setCategoryExternalId($this->faker->uuid)
            ->setCustomDetails($CustomDetails)
            ->setDescription(implode(', ', $this->faker->words()))
            ->setDiscountPrice($this->faker->randomNumber())
            ->setProductUrl($this->faker->imageUrl())
            ->setMainImageUrl($this->faker->imageUrl())
            ->setImageUrls($this->createImagesUrls())
            ->setMainCategory($this->faker->words(1)[0])
            ->setName($this->faker->words(1)[0])
            ->setPrice($this->faker->randomNumber())
            ->setUnitPrice($this->faker->randomNumber())
            ->setSystemDetails($SystemDetails)
            ->setQuantity($this->faker->numberBetween(1, 100000));

        return $Product;
    }

    /**
     * Creates CustomDetails objects
     * @return CustomDetailsEntity
     */
    protected function createCustomDetails()
    {
        $this->faker = Faker\Factory::create();

        $CustomDetails = new CustomDetailsEntity();
        $numberOfDetails = $this->faker->numberBetween(1, 5);

        while ($numberOfDetails) {
            $CustomDetails->set($this->faker->words(1)[0], $numberOfDetails);
            --$numberOfDetails;
        }

        return $CustomDetails;
    }

    /**
     * @return SystemDetailsEntity
     */
    protected function createSystemDetails()
    {
        $this->faker = Faker\Factory::create();

        $SystemDetails = new SystemDetailsEntity();
        $SystemDetails
            ->setBrand($this->faker->words(1)[0])
            ->setManufacturer($this->faker->words(1)[0])
            ->setPopularity($this->faker->numberBetween(1, 100))
            ->setGender($this->faker->randomKey(['-1', '0', '1', '2', '4']))
            ->setSeason($this->faker->randomKey(['spring', 'summer', 'autumn', 'winter']))
            ->setColor($this->faker->colorName)
            ->setBestseller($this->faker->boolean())
            ->setNewProduct($this->faker->boolean());

        return $SystemDetails;
    }

    /**
     * Creates images url
     * @return array
     */
    protected function createImagesUrls()
    {
        $this->faker = Faker\Factory::create();

        $imgs = [];
        $cImages = $this->faker->numberBetween(1, 5);

        for ($i=0; $i < $cImages; $i++) {
            $imgs[] = $this->faker->imageUrl();
        }

        return $imgs;
    }
}
