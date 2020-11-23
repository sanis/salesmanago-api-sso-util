<?php

namespace Tests\Services;


use PHPUnit\Framework\TestCase;
use SALESmanago\Entity\Configuration as Settings;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Contact\Address;
use SALESmanago\Entity\Contact\Options;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\ContactModel;
use SALESmanago\Services\ContactAndEventTransferService;

use Tests\Model\ContactModelTest;
use Tests\Model\EventModelTest;

class ContactAndEventTransferServiceTest extends TestCase
{
    public function testTransferContact()
    {
        $Settings = new Settings();
        $Contact = new Contact();

        /**
         * @todo here we need to create new owner instead using hard code data:
         */
        $Settings->setClientId('ye4vodnswfo6zp75')
            ->setDefaultApiKey()
            ->setApiSecret('123')
            ->generateSha()
            ->setOwner('qa.benhauer@gmx.com')
            ->setEndpoint('3.125.8.44:8080', false);


        $dummyData = ContactModelTest::prepareDummyDataForContactEntity();
        $contactFromPlatform = $dummyData['contactFromPlatform'];
        $addressFromPlatform = $dummyData['addressFromPlatform'];
        $optionsFromPlatform = $dummyData['optionsFromPlatform'];

        $Contact->set($contactFromPlatform);

        $Address = $Contact->getAddress();
        $Contact->setAddress(
            $Address->set($addressFromPlatform)
        );

        $Options = $Contact->getOptions();
        $Contact->setOptions(
            $Options->set($optionsFromPlatform)
        );

        $service = new ContactAndEventTransferService($Settings);

        $response = $service->transferContact($Contact);

        $this->assertArrayHasKey('success', $response);
        $this->assertArrayHasKey(Contact::C_ID, $response);
        $this->assertNotNull($response[Contact::C_ID]);
        $this->assertNotNull($response[Contact::C_ID]);
    }

    public function testTransferEvent()
    {
        $Settings = new Settings();
        $Event = new Event();

        /**
         * @todo here we need to create new owner instead using hard code data:
         */
        $Settings->setClientId('ye4vodnswfo6zp75')
            ->setDefaultApiKey()
            ->setApiSecret('123')
            ->generateSha()
            ->setOwner('qa.benhauer@gmx.com')
            ->setEndpoint('3.125.8.44:8080', false);

        $dummyData = EventModelTest::prepareDummyDataForEventEntity();

        $Event->set($dummyData);

        $service = new ContactAndEventTransferService($Settings);

        $response = $service->transferEvent($Event);

        $this->assertArrayHasKey('success', $response);
        $this->assertArrayHasKey(Event::EVENT_ID, $response);
        $this->assertNotNull($response[Event::EVENT_ID]);
        $this->assertNotNull($response[Event::EVENT_ID]);
    }

    public function testTransferBoth()
    {
        $Settings = new Settings();
        $Contact  = new Contact();
        $Event    = new Event(EventModelTest::prepareDummyDataForEventEntity());

        /**
         * @todo here we need to create new owner instead using hard code data:
         */
        $Settings->setClientId('ye4vodnswfo6zp75')
            ->setDefaultApiKey()
            ->setApiSecret('123')
            ->generateSha()
            ->setOwner('qa.benhauer@gmx.com')
            ->setEndpoint('3.125.8.44:8080', false);

        $dummyData           = ContactModelTest::prepareDummyDataForContactEntity();
        $contactFromPlatform = $dummyData['contactFromPlatform'];
        $addressFromPlatform = $dummyData['addressFromPlatform'];
        $optionsFromPlatform = $dummyData['optionsFromPlatform'];

        $Contact->set($contactFromPlatform);

        $Address = $Contact->getAddress();
        $Contact->setAddress(
            $Address->set($addressFromPlatform)
        );

        $Options = $Contact->getOptions();
        $Contact->setOptions(
            $Options->set($optionsFromPlatform)
        );

        $service = new ContactAndEventTransferService($Settings);

        $response = $service->transferBoth($Contact, $Event);

        $this->assertArrayHasKey('success', $response);

        $this->assertArrayHasKey(Contact::C_ID, $response);
        $this->assertNotNull($response[Contact::C_ID]);
        $this->assertNotNull($response[Contact::C_ID]);

        $this->assertArrayHasKey(Event::EVENT_ID, $response);
        $this->assertNotNull($response[Event::EVENT_ID]);
        $this->assertNotNull($response[Event::EVENT_ID]);
    }
}