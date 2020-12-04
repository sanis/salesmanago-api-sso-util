<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Contact\Address;
use SALESmanago\Entity\Contact\Options;
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
        $Address = $this->Contact->getAddress();
        $Options = $this->Contact->getOptions();

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
            Options::LANG        => $Options->getLang()
        ];

        if ($this->isSubscriptionStatusNoChangeChecker()) {
            $contactRequestArray[Options::F_OPT_IN]              = false;
            $contactRequestArray[Options::F_OPT_OUT]             = false;
            $contactRequestArray[Options::F_P_OPT_IN]            = false;
            $contactRequestArray[Options::F_P_OPT_OUT]           = false;
            $contactRequestArray[ApiDoubleOptIn::U_API_D_OPT_IN] = false;
        }

        if ($this->Contact->getOptions()->getIsUnSubscribes()) {
            $contactRequestArray[Options::F_OPT_IN]    = false;
            $contactRequestArray[Options::F_OPT_OUT]   = true;
            $contactRequestArray[Options::F_P_OPT_IN]  = false;
            $contactRequestArray[Options::F_P_OPT_OUT] = true;
        }

        if ($this->Contact->getOptions()->getIsSubscribes()) {
            $contactRequestArray[Options::F_OPT_IN]    = true;
            $contactRequestArray[Options::F_OPT_OUT]   = false;
            $contactRequestArray[Options::F_P_OPT_IN]  = true;
            $contactRequestArray[Options::F_P_OPT_OUT] = false;
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
     * Create Contact from Basic response;
     * @param array $response
     * @return Contact $Contact
     */
    public function getContactFromBasicResponse($response)
    {
        $this->Contact
            ->setName($response['name'])
            ->setEmail($response['email'])
            ->setPhone($response['phone'])
            ->setFax($response['fax'])
            ->setScore($response['score'])
            ->setState($response['state'])
            ->setCompany($response['company'])
            ->setExternalId($response['externalId'])
            ->setContactId($response['contactId'])
            ->setBirthdayYear($response['birthdayYear'])
            ->setBirthdayMonth($response['birthdayMonth'])
            ->setBirthdayDay($response['birthdayDay']);

        $this->Contact->getOptions()
            ->setOptedOut($response['optedOut'])
            ->setOptedOutPhone($response['optedOutPhone'])
            ->setDeleted($response['deleted'])
            ->setInvalid($response['invalid'])
            ->setModifiedOn($response['modifiedOn'])
            ->setCreatedOn($response['createdOn'])
            ->setLastVisit($response['lastVisit']);

        $this->Contact->getAddress()
            ->setStreetAddress($response['address']['streetAddress'])
            ->setZipCode($response['address']['zipCode'])
            ->setCity($response['address']['city'])
            ->setCountry($response['address']['country']);

        return $this->Contact;
    }

    /**
     * Checks if subscription status no change
     * @return bool
     */
    protected function isSubscriptionStatusNoChangeChecker()
    {
        return (!$this->Contact->getOptions()->getIsSubscribes()
            && $this->Contact->getOptions()->getIsSubscriptionStatusNoChange()
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
