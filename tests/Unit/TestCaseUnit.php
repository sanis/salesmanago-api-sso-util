<?php


namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;

use SALESmanago\Controller\LoginController;
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
        $userEmail = getenv('userEmail');
        $userPass = getenv('userPass');

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