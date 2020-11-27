<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Services\SynchronizationService as SyncService;

class ContactAndEventTransferController
{
    protected $settings;
    protected $service;

    /**
     * @var SyncService
     */
    protected $syncService;

    public function __construct(Configuration $settings)
    {
        $this->settings = $settings;
        $this->service  = new ContactAndEventTransferService($this->settings);
        $this->syncService = new SyncService($this->settings);
    }

    public function transferBoth(Contact $Contact, Event $Event)
    {
        return array_merge(
            [
                'settings' =>
                    $this->settings->setRequireSyncronization($this->syncService->isNeedSyncContactEmailStatus($Contact))
            ],
            $this->service->transferBoth($Contact, $Event)
        );
    }

    public function transferEvent(Event $Event)
    {
        return $this->service->transferEvent($Event);
    }

    public function transferContact(Contact $Contact)
    {
       return array_merge(
           [
               'settings' =>
                   $this->settings->setRequireSyncronization($this->syncService->isNeedSyncContactEmailStatus($Contact))
           ],
           $this->service->transferContact($Contact)
       );
    }

}
