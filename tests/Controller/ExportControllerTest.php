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
            ->setEmail('ruslan.barlozhetskyi@salesmanago.pl')
            ->setPass('04ru06sl94an');

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
        $response = $loginController->login($user);
        $exportController = new ExportController($response->getField('conf'));
        $exportController->export($eventCollection);
    }
}