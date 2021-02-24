<?php

namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Response;
use SALESmanago\Services\UserAccountService;
use SALESmanago\Entity\User;
use SALESmanago\Exception\Exception;

/**
 * Class LoginController
 */
class LoginController
{
    /**
     * @var ConfigurationInterface
     */
    protected $conf;

    /**
     * @var UserAccountService
     */
    protected $service;

    /**
     * LoginController constructor.
     * @param ConfigurationInterface $conf
     */
    public function __construct(ConfigurationInterface $conf)
    {
        Configuration::setInstance($conf);
        $this->conf = $conf;
        $this->service = new UserAccountService($this->conf);
    }

    /**
     * @param User $User
     * @throws Exception
     * @return Response
     */
    public function login(User $User) {
         return $this->service->login($User);
    }
}
