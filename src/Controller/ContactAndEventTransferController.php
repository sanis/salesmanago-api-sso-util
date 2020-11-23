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
        return array_merge($this->service->transferBoth($Contact, $Event),
            [
                'settings' =>
                    $this->settings->setRequireSyncronization($this->syncService->isNeedSyncContactEmailStatus($Contact))
            ]
        );
    }

    public function transferEvent(Event $Event)
    {
        return $this->service->transferEvent($Event);
    }

    public function transferContact(Contact $Contact)
    {
       return array_merge(
           $this->service->transferContact($Contact),
           [
               'settings' =>
                   $this->settings->setRequireSyncronization($this->syncService->isNeedSyncContactEmailStatus($Contact))
           ]
       );
    }

}
