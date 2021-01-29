<?php

namespace SALESmanago\Controller;

use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Response;
use SALESmanago\Services\UserAccountService;
use SALESmanago\Entity\User;

use SALESmanago\Exception\Exception;

/**
 * Class LoginAccountController
 * @package SALESmanago\Controller
 */
class LoginController
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
     * @param User $User
     * @throws Exception
     * @return Response
     */
    public function login(User $User) {
         return $this->service->login($User);
    }

    /**
     * @return string
     */
    public function getRedirectToAppLink()
    {
       return $this->service->getRedirectToAppUrl();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getListOwnersEmails()
    {
       return $this->service->listOwnersEmails();
    }
}
