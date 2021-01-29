<?php

namespace SALESmanago\Factories;

use SALESmanago\Controller\ContactAndEventTransferController;

class FactoryOrganizer
{
    const LOGIN_ACCOUNT_C       = 'LoginAccountC';
    const CONTACT_AND_EVENT_TRANSFER_C = 'ContactAndEventTransferC';

    const USER_ACCESS_E        = 'UserAccessE';

    const COOKIES_CLIENT       = ConnectSalesManagoController::COOKIES_CLIENT;
    const COOKIES_EXT_EVENT    = ConnectSalesManagoController::COOKIES_EXT_EVENT;

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
