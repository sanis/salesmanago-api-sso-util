<?php


namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;

use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\ApiV3ConfigurationInterface;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\User;
use SALESmanago\Exception\Exception;


class TestCaseUnit extends TestCase
{
    /**
     * @return mixed|Configuration
     * @throws Exception
     */
    protected function initConf()
    {
        $userEmail = 'ruslan.barlozhetskyi@salesmanago.pl'; #getenv('userEmail');
        $userPass = '04ru06sl94an'; #getenv('userPass');

        $conf = Configuration::getInstance();
        $user = new User();

        $user
            ->setEmail($userEmail)
            ->setPass($userPass);

        $loginController = new LoginController($conf);
        $loginController->login($user);//this one for property configuration create

        return $conf;
    }

    /**
     * @return ApiV3ConfigurationInterface
     */
    protected function initConfApiV3(): ApiV3ConfigurationInterface
    {
        $conf = $this->initConf();

        $conf
            ->setApiV3Endpoint('https://api.salesmanago.com')
            ->setEndpoint('https://api.salesmanago.com')
            ->setApiKeyV3(getenv('API_KEY'));

        return $conf;
    }

    /**
     * @param $name
     * @param mixed $classObj
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function getMethod($name, $classObj)
    {
        $class = new ReflectionClass($classObj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}