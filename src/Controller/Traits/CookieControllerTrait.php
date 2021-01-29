<?php


namespace SALESmanago\Traits\Controller;


use SALESmanago\Adapter\CookieManagerAdapter;

trait CookieControllerTrait
{
    /**
     * @var CookieManagerAdapter
     */
    protected $CookieManager;

    /**
     * @param CookieManagerAdapter $CookieManager
     * @return $this
     */
    public function setCookieManager(CookieManagerAdapter $CookieManager)
    {
        $this->CookieManager = $CookieManager;
        return $this;
    }
}