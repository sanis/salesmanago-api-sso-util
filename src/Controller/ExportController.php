<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
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
     * @param ConfigurationInterface $conf
     */
    public function __construct(ConfigurationInterface $conf)
    {
        Configuration::setInstance($conf);
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