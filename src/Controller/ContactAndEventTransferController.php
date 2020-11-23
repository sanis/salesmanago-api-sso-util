<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;

class ContactAndEventTransferController
{
    protected $settings;
    protected $service;

    public function __construct(Configuration $settings)
    {
        $this->service  = new ContactAndEventTransferService($settings);
        $this->settings = $settings;
    }

    public function transferBoth(Contact $Contact, Event $Event)
    {
        return $this->service->transferBoth($Contact, $Event);
    }

    public function transferEvent(Event $Event)
    {
        return $this->service->transferEvent($Event);
    }

    public function transferContact(Contact $Contact)
    {
       return $this->service->transferContact($Contact);
    }
}
