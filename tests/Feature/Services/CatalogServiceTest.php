<?php

namespace Tests\Feature\Services;

use SALESmanago\Services\ApiV3\CatalogService;
use Tests\Feature\TestCaseUnit;

class CatalogServiceTest extends TestCaseUnit
{
    public function testGetCatalogs()
    {
        $Conf = $this->initConfApiV3();
        $CatalogService = new CatalogService($Conf);
        var_dump($CatalogService->getCatalogs());
    }

//    public function testGetCatalogsAfterCreate()
//    {
//        $Conf = $this->initConfApiV3();
//        $CatalogService = new CatalogService($Conf);
//
//        var_dump($CatalogService->getCatalogs());
//    }

    public function testCreateCatalog()
    {
        //todo
    }
}