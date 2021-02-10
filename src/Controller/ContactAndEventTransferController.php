<?php


namespace SALESmanago\Controller;


use SALESmanago\Controller\Traits\TemporaryStorageControllerTrait;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Response;
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
     * @return Response
     * @throws Exception
     */
    public function transferBoth(Contact $Contact, Event $Event)
    {
        if ($this->ignoreService->isContactIgnored($Contact)) {
            $Response = $this->ignoreService->getDeclineResponse();
            return $Response->setField('conf', $this->conf);
        }

        Configuration::getInstance()->setRequireSynchronization(
            $this->syncService->isNeedSyncContactEmailStatus(clone $Contact)
        );

        $Response = $this->service->transferBoth($Contact, $Event);
        return $Response->setField('conf', Configuration::getInstance());

    }

    /**
     * @param Event $Event
     * @return Response
     * @throws Exception
     */
    public function transferEvent(Event $Event)
    {
        return $this->service->transferEvent($Event);
    }

    /**
     * @param Contact $Contact
     * @return Response
     * @throws Exception
     */
    public function transferContact(Contact $Contact)
    {
        if ($this->ignoreService->isContactIgnored($Contact)) {
            return $this->ignoreService->getDeclineResponse();
        }

        Configuration::getInstance()
            ->setRequireSynchronization(
            $this->syncService->isNeedSyncContactEmailStatus(clone $Contact)
        );

        $Response = $this->service->transferContact($Contact);
        return $Response->setField('conf', Configuration::getInstance());
    }

}
