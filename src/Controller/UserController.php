<?php


namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Services\UserAccountService;

use SALESmanago\Exception\Exception;

class UserController
{
    /**
     * @var Configuration
     */
    protected $conf;

    /**
     * @var UserAccountService
     */
    protected $service;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
        $this->service = new UserAccountService($conf);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getOwnersList()
    {
        return $this->service->listOwnersList();
    }
}