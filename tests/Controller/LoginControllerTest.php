<?php


namespace Tests\Controller;


use PHPUnit\Framework\TestCase;
use Faker;
use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\User;

class LoginControllerTest extends TestCase
{
        const
            USER_EMAIL = 'ruslan.barlozhetskyi@salesmanago.pl',
            USER_PASS = '04ru06sl94an';

        public function testLoginSuccess()
        {
            $conf = new Configuration();
            $User = new User();
            $loginController = new LoginController($conf);

            $User = $User
                ->setEmail(self::USER_EMAIL)
                ->setPass(self::USER_PASS);

            $response = $loginController->login($User);

            var_dump($response);
        }
}