<?php

namespace SALESmanago\Controller;

use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Services\SearchEngineService;


class SearchEngineController
{

    public function __construct()
    {
        $this->service = new SearchEngineService();
    }

    public function searchEngineProcess($search_query, $sei, $vsi, $smc=null)
    {
        try {
            $responseData = $this->service->searchEngineProcess($search_query, $sei, $vsi, $smc);
            return $responseData;
        } catch (SalesManagoException $e) {
            return $e->getSalesManagoMessage();
        }

    }

}