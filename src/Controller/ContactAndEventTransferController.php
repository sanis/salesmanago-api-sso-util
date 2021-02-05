<?php


namespace SALESmanago\Controller;


use SALESmanago\Controller\Traits\TemporaryStorageControllerTrait;
use SALESmanago\Entity\Configuration;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Services\SynchronizationService as SyncService;
use SALESmanago\Services\CheckIfIgnoredService as IgnoreService;

class ContactAndEventTransferController
{
    //this one is to set cookies and sessions:
    use TemporaryStorageControllerTrait;

    protected $conf;
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
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf)
    {
        $this->conf          = $conf;
        $this->service       = new ContactAndEventTransferService($this->conf);
        $this->syncService   = new SyncService($this->conf);
        $this->ignoreService = new IgnoreService($this->conf);
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
                ['conf' => $this->conf]
            );
        }

        return array_merge(
            [
                'conf' =>
                    $this->conf->setRequireSynchronization(
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
        return array_merge(
            [
                Configuration::COOKIE_TTL => $this->conf->getCookieTtl()
            ],
            $this->service->transferEvent($Event)
        );
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
                ['conf' => $this->conf]
            );
        }

        return array_merge(
            [
                'conf' =>
                    $this->conf->setRequireSynchronization(
                        $this->syncService->isNeedSyncContactEmailStatus(clone $Contact)
                    )
            ],
            $this->service->transferContact($Contact)
        );
    }

}
