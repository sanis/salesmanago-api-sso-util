<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Collections\Collection;
use SALESmanago\Services\ContactAndEventTransferService;
use SALESmanago\Services\ExportService;
use SALESmanago\Entity\Response;
use SALESmanago\Exception\Exception;

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

    /**
     * export collection
     * @param Collection $collection
     * @return Response
     * @throws Exception
     */
    public function export(Collection $collection)
    {
        return $this->service->export($collection);
    }
}