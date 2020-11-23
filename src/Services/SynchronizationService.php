<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Model\ContactModel;
use SALESmanago\Model\SettingsModel;
use SALESmanago\Controller\ContactController;

class SynchronizationService
{
    const
        REQ_SYNC = 'requiresSynchronization',
        SYNC_TYPE_EMAIL_OPTIN = 'emailSubscribtionStatusSync';

    private $Settings;
    private $SettingsModel;

    private $requiresSynchronization = false;
    private $Contact;

    public function __construct(Configuration $Settings)
    {
        $this->Settings = $Settings;
    }

    /**
     * @return array
     */
    public function isNeedSyncContactEmailStatus(Contact $Contact)
    {
        if (!$this->checkActiveState()) {
            return false;
        }

        $this->Contact = $Contact;
        $contactController = new ContactController($this->Settings);
        $ContactBasic = $contactController->getContactBasic($this->Contact);

        return $this->checkSyncContactEmailStatus($ContactBasic);
    }

    /**
     * Checker for SynncContactEmailStatus
     * @param Contact|null $ContactBasic
     * @return bool
     */
    protected function checkSyncContactEmailStatus($ContactBasic)
    {
        if (!$this->Settings->getActiveSynchronization()
            || $ContactBasic == null
            || $ContactBasic->getOptions()->getOptedOut()
            || $this->Contact->getOptions()->getIsSubscribes()
            || $this->Contact->getOptions()->getIsUnSubscribes()
            || $this->Contact->getOptions()->getForceOptIn() == true
            || $this->Settings->getApiDoubleOptIn()->getEnabled() == true
        ) {
            return false;
        }

        return true;
    }

    /**
     * Check if service is activated;
     * @return Configuration
     */
    protected function checkActiveState(){
        return $this->Settings->getActiveSynchronization();
    }
}
