<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Model\ContactModel;
use SALESmanago\Model\ConfModel;
use SALESmanago\Controller\ContactController;

class SynchronizationService
{
    const
        REQ_SYNC = 'requiresSynchronization',
        SYNC_TYPE_EMAIL_OPTIN = 'emailSubscribtionStatusSync';

    private $conf;
    private $ConfModel;

    private $requiresSynchronization = false;
    private $Contact;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
    }

    /**
     * @param Contact $Contact
     * @return bool
     * @throws \SALESmanago\Exception\Exception
     */
    public function isNeedSyncContactEmailStatus(Contact $Contact)
    {
        if (!$this->conf->getActiveSynchronization()) {
            return false;
        }

        $this->Contact = $Contact;
        $contactController = new ContactController($this->conf);
        $ContactBasic = $contactController->getContactBasic($this->Contact);

        return $this->checkSyncContactEmailStatus($ContactBasic);
    }

    /**
     * Checker for SyncContactEmailStatus
     * @param Contact|null $ContactBasic
     * @return bool
     */
    protected function checkSyncContactEmailStatus($ContactBasic)
    {
        if (!$this->conf->getActiveSynchronization()
            || $ContactBasic == null
            || $ContactBasic->getOptions()->getOptedOut()
            || $this->Contact->getOptions()->getIsSubscribes()
            || $this->Contact->getOptions()->getIsUnSubscribes()
            || $this->Contact->getOptions()->getForceOptIn() == true
            || $this->conf->getApiDoubleOptIn()->getEnabled() == true
        ) {
            return false;
        }

        return true;
    }
}
