<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Collections\Collection;
use SALESmanago\Services\ContactAndEventTransferService;
use SALESmanago\Services\ExportService;

class ExportController
{
    /**
     * @var Configuration
     */
    protected $conf;

    /**
     * @var ContactAndEventTransferService
     */
    protected $service;

    /**
     * ExportController constructor.
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
        $this->service = new ExportService($conf);
    }

    //export collection
    public function export(Collection $collection)
    {
        $this->service->export($collection);
    }

    //return response from promise export
    public function getExportResponse(){}
}