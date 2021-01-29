<?php


namespace SALESmanago\Adapter;


interface CookieManagerAdapter
{
    const
        //name for cookie with SALESmanano eventId:
        EVENT_COOKIE = 'smevent',
        //name for cookie with SALESmanano contactId:
        CLIENT_COOKIE = 'smclient';

    /**
     * @param string $name
     * @param int $expiry
     * @param bool $httpOnly
     * @param $path
     * @return bool
     */
    public function setCookie($name, $expiry, $httpOnly = false, $path = '/');

    /**
     * @param string $name
     * @return bool
     */
    public function deleteCookie($name);
}