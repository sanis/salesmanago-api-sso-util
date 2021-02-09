<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Response;

class CheckIfIgnoredService
{
    const
        IS_IGNORED = 'isIgnored',
        IGNORED_MESSAGE = "Contact was ignored";

    private $conf;

    private $Contact;

    public function __construct(Configuration $conf)
    {
        $this->conf = $conf;
    }

    /**
     * @return Response
     */
    public function getDeclineResponse()
    {
        $Response = new Response();

        return $Response
            ->setStatus(false)
            ->setMessage(array(CheckIfIgnoredService::IGNORED_MESSAGE))
            ->setField('contactId', null)
            ->setField('eventId', null);
    }

    /**
     * @param Contact $Contact
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
        if (!empty($this->conf->getIgnoreDomain())
            && is_array($this->conf->getIgnoreDomain())
            && !empty($this->Contact->getEmail())) {
            $emailDomain = explode('@', $this->Contact->getEmail())[1];
            return in_array($emailDomain, $this->conf->getIgnoreDomain());
        }
        return false; //no reason to ignore Contact
    }
}
