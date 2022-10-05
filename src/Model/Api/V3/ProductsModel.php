<?php

namespace SALESmanago\Model\Api\V3;

use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Model\Collections\Api\V3\ProductsCollectionInterface;

class ProductsModel
{
    /**
     * @param CatalogEntityInterface $Catalog
     * @param ProductsCollectionInterface $ProductsCollection
     * @return false|string
     * @throws ApiV3Exception
     */
    public function getProductsToUpsert(
        CatalogEntityInterface $Catalog,
        ProductsCollectionInterface $ProductsCollection
    ) {
        if (empty($Catalog->getCatalogId())) {
            throw new ApiV3Exception('Products model: catalog id is empty', '500');
        }

        $productsArray = $ProductsCollection->toArray();

        return json_encode([
            CatalogEntityInterface::CATALOG_ID => $Catalog->getCatalogId(),
            ProductsCollectionInterface::PRODUCTS => $productsArray
        ]);
    }
}