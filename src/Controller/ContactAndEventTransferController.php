<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Settings;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;

use SALESmanago\Entity\Contact\Contact;
/*use SALESmanago\Entity\Contact\Event;*/

class ContactAndEventTransferController
{
    protected $settings;
    protected $service;

    public function __construct(Settings $settings)
    {
        $this->service  = new ContactAndEventTransferService($settings);
        $this->settings = $settings;
    }

    public function transfer($Contact = null, $Event = null)
    {
        if ($Contact == null && $Event == null) {
           throw new Exception('No data to transfer');
        }

        $this->service->transfer($Contact, $Event);
    }
}