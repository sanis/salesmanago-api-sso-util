<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Contact\Address;
use SALESmanago\Entity\Contact\Options;
use SALESmanago\Entity\Contact\Properties;
use SALESmanago\Entity\Contact\ApiDoubleOptIn;

use SALESmanago\Entity\Configuration as Settings;
use SALESmanago\Exception\Exception;

use SALESmanago\Helper\DataHelper;


class ContactModel
{
    protected $Contact;
    protected $Settings;

    public function __construct(Contact $Contact, Settings $Settings)
    {
        $this->Contact = $Contact;
        $this->Settings = $Settings;
    }

    /**
     * @throws Exception
     * @return array
     */
    public function getContactForUnionTransfer()
    {
        $Address    = $this->Contact->getAddress();
        $Options    = $this->Contact->getOptions();
        $Properties = $this->Contact->getProperties();

        $contactRequestArray = [
            Settings::CLIENT_ID => $this->Settings->getClientId(),
            Options::ASYNC      => $Options->getAsync(),
            Contact::CONTACT    => [
                Contact::EMAIL   => $this->Contact->getEmail(),
                Contact::FAX     => $this->Contact->getFax(),
                Contact::NAME    => $this->Contact->getName(),
                Contact::PHONE   => $this->Contact->getPhone(),
                Contact::COMPANY => $this->Contact->getCompany(),
                Contact::STATE   => $this->Contact->getState(),
                Contact::ADDRESS => [
                    Address::STREET_AD => $Address->getStreetAddress(),
                    Address::ZIP_CODE  => $Address->getZipCode(),
                    Address::CITY      => $Address->getCity(),
                    Address::COUNTRY   => $Address->getCountry()
                ],
            ],
            Settings::OWNER      => $this->Settings->getOwner(),
            Options::N_EMAIL     => $Options->getNewEmail(),
            Options::F_OPT_IN    => $Options->getForceOptIn(),
            Options::F_OPT_OUT   => $Options->getForceOptOut(),
            Options::F_P_OPT_IN  => $Options->getForcePhoneOptIn(),
            Options::F_P_OPT_OUT => $Options->getForcePhoneOptOut(),
            Options::TAGS        => $Options->getTags(),
            Options::R_TAGS      => $Options->getRemoveTags(),
            Contact::BIRTHDAY    => $this->Contact->getBirthday(),// attention
            Address::PROVINCE    => $Address->getProvince(),// attention
            Options::LANG        => $Options->getLang(),
            Properties::PROPERTIES => $Properties->getProperties()
        ];

        if ($this->isSubscribtionStatusNoChangeChecker()) {
            $contactRequestArray[Options::F_OPT_IN]              = false;
            $contactRequestArray[Options::F_OPT_OUT]             = false;
            $contactRequestArray[Options::F_P_OPT_IN]            = false;
            $contactRequestArray[Options::F_P_OPT_OUT]           = false;
            $contactRequestArray[ApiDoubleOptIn::U_API_D_OPT_IN] = false;
        }

        if ($this->apiDoubleOptInChecker()) {
            $ApiDoubleOptIn = $this->Settings->getApiDoubleOptIn();

            $contactRequestArray = array_merge([
                ApiDoubleOptIn::U_API_D_OPT_IN        => $ApiDoubleOptIn->getEnabled(),
                ApiDoubleOptIn::D_OPT_IN_EMAIL_ACC_ID => $ApiDoubleOptIn->getAccountId(),
                ApiDoubleOptIn::D_OPT_IN_TEMPLATE_ID  => $ApiDoubleOptIn->getTemplateId(),
                ApiDoubleOptIn::D_OPT_IN_EMAIL_SUBJ   => $ApiDoubleOptIn->getSubject()
            ], $contactRequestArray);
        }

        return DataHelper::filterDataArray($contactRequestArray);
    }

    /**
     * Create contact request part for contact basic method
     */
    public function getContactForBasicRequest()
    {
        return DataHelper::filterDataArray([
            Settings::CLIENT_ID => $this->Settings->getClientId(),
            Options::ASYNC => $this->Contact->getOptions()->getAsync(),
            Contact::EMAIL => [
                $this->Contact->getEmail()
            ],
            Settings::OWNER => $this->Settings->getOwner(),
        ]);
    }

    /**
     * Create Contact from Basic responce;
     * @param $response
     * @retrun Contact
     */
    public function getContactFromBasicResponse($responseContact)
    {
        $this->Contact
            ->setName($responseContact['name'])
            ->setEmail($responseContact['email'])
            ->setPhone($responseContact['phone'])
            ->setFax($responseContact['fax'])
            ->setScore($responseContact['score'])
            ->setState($responseContact['state'])
            ->setCompany($responseContact['company'])
            ->setExternalId($responseContact['externalId'])
            ->setContactId($responseContact['contactId'])
            ->setBirthdayYear($responseContact['birthdayYear'])
            ->setBirthdayMonth($responseContact['birthdayMonth'])
            ->setBirthdayDay($responseContact['birthdayDay']);

        $this->Contact->getOptions()
            ->setOptedOut($responseContact['optedOut'])
            ->setOptedOutPhone($responseContact['optedOutPhone'])
            ->setDeleted($responseContact['deleted'])
            ->setInvalid($responseContact['invalid'])
            ->setModifiedOn($responseContact['modifiedOn'])
            ->setCreatedOn($responseContact['createdOn'])
            ->setLastVisit($responseContact['lastVisit']);

        $this->Contact->getAddress()
            ->setStreetAddress($responseContact['address']['streetAddress'])
            ->setZipCode($responseContact['address']['zipCode'])
            ->setCity($responseContact['address']['city'])
            ->setCountry($responseContact['address']['country']);

        return $this->Contact;
    }

    protected function isSubscribtionStatusNoChangeChecker()
    {
        return (!$this->Contact->getOptions()->getIsSubscribes()
            && $this->Contact->getOptions()->getIsSubscribtionStatusNoChange()
            && !$this->Contact->getOptions()->getForceOptIn());
    }

    /**
     * Checking for need add apiDoubleOptIn to request;
     *
     * @return bool
     */
    protected function apiDoubleOptInChecker()
    {
        $isApiDoubleOptInEnabled = $this->Settings->getApiDoubleOptIn()->getEnabled();

        if ($isApiDoubleOptInEnabled && $this->Contact->getOptions()->getIsSubscribes()) {
           return true;
        }
        return false;
    }
}
