<?php


namespace SALESmanago\Controller\Traits;


use SALESmanago\Adapter\ContactStatusSynchronizationManagerAdapter as ContactSyncAdapter;
use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;

trait ContactStatusSynchronizationTrait
{
    /**
     * @var ContactSyncAdapter
     */
    private $ContactSyncManager;

    /**
     * @param ContactSyncAdapter $ContactSyncManager
     * @return $this
     */
    public function setContactSyncManager(ContactSyncAdapter $ContactSyncManager)
    {
        $this->ContactSyncManager = $ContactSyncManager;
        return $this;
    }
}