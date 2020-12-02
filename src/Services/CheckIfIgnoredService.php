<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;

class CheckIfIgnoredService
{
    const
        IS_IGNORED = 'isIgnored';

    private $Settings;
    private $SettingsModel;

    private $Contact;

    public function __construct(Configuration $Settings)
    {
        $this->Settings = $Settings;
    }

    /**
     * @return bool
     */
    public function isContactIgnored(Contact $Contact)
    {
        $this->Contact = $Contact;

        if($this->checkIgnoreDomain()) {
            return true;
        }
        // <--put other functions to ignore contact here -->
        return false; //contact will not be ignored

    }

    /**
     * Checker for IgnoredDomains.
     * @param null
     * @return bool Returns true if contact should be ignored
     */
    protected function checkIgnoreDomain()
    {
        if (!empty($this->Settings->getIgnoreDomain())
            && is_array($this->Settings->getIgnoreDomain())
            && !empty($this->Contact->getEmail())) {
            $emailDomain = explode('@', $this->Contact->getEmail())[1];
            return in_array($emailDomain, $this->Settings->getIgnoreDomain());
        }
        return false; //no reason to ignore Contact
    }
}
