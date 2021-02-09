<?php


namespace Tests\Controller;


use PHPUnit\Framework\TestCase;
use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\User;
use SALESmanago\Exception\Exception;

class LoginControllerTest extends TestCase
{
        public function testLoginSuccess()
        {
            $conf = Configuration::getInstance();
            $User = new User();
            $loginController = new LoginController($conf);

            $User = $User
                ->setEmail('semowet930@boldhut.com')
                ->setPass('#Salesmanago123');

            $response = $loginController->login($User);

            $this->assertInstanceOf('SALESmanago\Entity\Response', $response);
            $this->assertEquals(true, $response->getStatus());
        }
}