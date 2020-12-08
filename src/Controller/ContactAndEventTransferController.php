<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Services\SynchronizationService as SyncService;
use SALESmanago\Services\CheckIfIgnoredService as IgnoreService;

class ContactAndEventTransferController
{
    protected $settings;
    protected $service;

    /**
     * @var SyncService
     */
    protected $syncService;

    /**
     * @var IgnoreService
     */
    protected $ignoreService;

    /**
     * ContactAndEventTransferController constructor.
     * @param Configuration $settings
     */
    public function __construct(Configuration $settings)
    {
        $this->settings      = $settings;
        $this->service       = new ContactAndEventTransferService($this->settings);
        $this->syncService   = new SyncService($this->settings);
        $this->ignoreService = new IgnoreService($this->settings);
    }

    /**
     * @param Contact $Contact
     * @param Event $Event
     * @return array
     * @throws Exception
     */
    public function transferBoth(Contact $Contact, Event $Event)
    {
        if($this->ignoreService->isContactIgnored($Contact)) {
            return array_merge(
                $this->ignoreService->getDeclineResponse(),
                ['settings' => $this->settings]
            );
        }
        return array_merge(
            [
                'settings' =>
                    $this->settings->setRequireSynchronization(
                        $this->syncService->isNeedSyncContactEmailStatus(clone $Contact)
                    )
            ],
            $this->service->transferBoth($Contact, $Event)
        );
    }

    /**
     * @param Event $Event
     * @return array
     * @throws Exception
     */
    public function transferEvent(Event $Event)
    {
        return $this->service->transferEvent($Event);
    }

    /**
     * @param Contact $Contact
     * @return array
     * @throws Exception
     */
    public function transferContact(Contact $Contact)
    {
        if($this->ignoreService->isContactIgnored($Contact)) {
            return array_merge(
                $this->ignoreService->getDeclineResponse(),
                ['settings' => $this->settings]
            );
        }

        return array_merge(
            [
                'settings' =>
                    $this->settings->setRequireSynchronization(
                        $this->syncService->isNeedSyncContactEmailStatus(clone $Contact)
                    )
            ],
            $this->service->transferContact($Contact)
        );
    }

}
