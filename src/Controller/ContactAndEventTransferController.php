<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Settings;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;

class ContactAndEventTransferController
{
    protected $settings;
    protected $service;

    public function __construct(Settings $settings)
    {
        $this->service  = new ContactAndEventTransferService($settings);
        $this->settings = $settings;
    }

    public function transferBoth(Contact $Contact, Event $Event)
    {
        try {
            return $this->service->transferBoth($Contact, $Event);
        } catch (Exception $exception) {

        }

    }

    public function transferEvent(Event $Event)
    {
        try {
            return $this->service->transferEvent($Event);
        } catch (Exception $e) {

        }

    }

    public function transferContact(Contact $Contact)
    {
        try {
            return $this->service->transferContact($Contact);
        } catch (Exception $e) {

        }

    }
}