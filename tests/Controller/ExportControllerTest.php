<?php


namespace Tests\Controller;

use Faker;
use PHPUnit\Framework\TestCase;
use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\User;
use SALESmanago\Controller\ExportController;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Collections\EventsCollection;

class ExportControllerTest extends TestCase
{
    public function testExportEventsSuccess()
    {
        $faker = Faker\Factory::create();
        $conf = Configuration::getInstance();
        $user = new User();
        $eventCollection = new EventsCollection();

        $user
            ->setEmail('semowet930@boldhut.com')
            ->setPass('#Salesmanago123');

        for ($i=0; $i<=100; $i++) {
            $event = new Event();
            $eventCollection->addItem(
                $event
                    ->setEmail($faker->email)
                    ->setContactExtEventType(Event::EVENT_TYPE_PURCHASE)
                    ->setProducts($faker->uuid)
                    ->setDescription($faker->text(200))
                    ->setDate($faker->time())
                    ->setExternalId($faker->uuid)
                    ->setLocation($faker->sha1)
                    ->setValue($faker->randomNumber())
            );
        }

        $loginController = new LoginController($conf);
        $loginController->login($user);//this one for property configuration create
        $exportController = new ExportController($conf);
        $exportController->export($eventCollection);
    }

    public function testExportContactsSuccess()
    {
        $faker = Faker\Factory::create();
        $conf = Configuration::getInstance();
        $user = new User();

        $user
            ->setEmail('ruslan.barlozhetskyi@salesmanago.pl')
            ->setPass('04ru06sl94an');
    }
}