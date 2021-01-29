<?php


namespace SALESmanago\Controller\Traits;


use SALESmanago\Traits\Controller\CookieControllerTrait;
use SALESmanago\Traits\Controller\SessionControllerTrait;
use SALESmanago\Adapter\CookieManagerAdapter;
use SALESmanago\Adapter\SessionManagerAdapter;

trait TemporaryStorageControllerTrait
{
    use CookieControllerTrait;
    use SessionControllerTrait;

    /**
     * @param int $id
     * @return bool
     */
    public function setSmEvent($id)
    {
        if(!$this->checkIfAdaptersSet()){
            return false;
        }
        //@todo set to session set to cookie use $this->conf to get cookie smevent expiry time;
        return true;
    }

    /**
     * @return bool
     */
    public function unsetSmEvent()
    {
        if(!$this->checkIfAdaptersSet()){
            return false;
        }
        //@todo unset session unset cookie;
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setSmClient($id)
    {
        if(!$this->checkIfAdaptersSet()){
            return false;
        }
        //@todo set to session set to cookie;
        return true;
    }

    /**
     * @return bool
     */
    public function unsetSmClient()
    {
        if(!$this->checkIfAdaptersSet()){
            return false;
        }
        //@todo unset session unset cookie;
        return true;
    }


    /**
     * @return bool
     */
    private function checkIfSessionAdapterSet()
    {
        //@todo;
        return false;
    }

    /**
     * @return bool
     */
    private function checkIfCookieAdapterSet()
    {
        //@todo;
        return false;
    }

    /**
     * @return bool
     */
    private function checkIfAdaptersSet(){
        if(!$this->checkIfSessionAdapterSet()
            && !$this->checkIfCookieAdapterSet()
        ) {
            return false;
        }

        return true;
    }
}