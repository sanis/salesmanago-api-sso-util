<?php

namespace SALESmanago\Factories;

use SALESmanago\Adapter\CookieManagerAdapter;
use SALESmanago\Controller\ContactAndEventTransferController;

use SALESmanago\Controller\LoginController;

class FactoryOrganizer
{
    const LOGIN_ACCOUNT_C       = 'LoginAccountC';
    const CONTACT_AND_EVENT_TRANSFER_C = 'ContactAndEventTransferC';

    public function getInst($const, $paramsFirst = null, $paramsSecond = null)
    {
        switch ($const) {
            case self::LOGIN_ACCOUNT_C:
                return new LoginController($paramsFirst, $paramsSecond);
            case self::CONTACT_AND_EVENT_TRANSFER_C:
                return new ContactAndEventTransferController($paramsFirst);
            default:
                return false;
            break;
        }
    }
}
