<?php


namespace SALESmanago\Traits\Controller;


use SALESmanago\Adapter\SessionManagerAdapter;

trait SessionControllerTrait
{
    /**
     * @var SessionManagerAdapter
     */
    protected $SessionManager;

    /**
     * @param SessionManagerAdapter $SessionManager
     * @return $this
     */
    public function setSessionManager(SessionManagerAdapter $SessionManager)
    {
        $this->SessionManager = $SessionManager;
        return $this;
    }
}