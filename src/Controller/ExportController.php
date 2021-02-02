<?php


namespace SALESmanago\Controller\Export;


use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Collections\Collection;
use SALESmanago\Services\CheckIfIgnoredService as IgnoreService;
use SALESmanago\Services\ContactAndEventTransferService;
use SALESmanago\Services\SynchronizationService as SyncService;

class ExportController
{
    /**
     * @var Configuration
     */
    protected $settings;

    /**
     * @var ContactAndEventTransferService
     */
    protected $service;

    /**
     * ExportController constructor.
     * @param Configuration $settings
     */
    public function __construct(Configuration $settings)
    {
        $this->settings      = $settings;
        $this->service       = ''; //@TODO add service;
    }

    //export collection
    public function export(Collection $collection)
    {

    }

    //return response from promise export
    public function getExportResponse(){}
}