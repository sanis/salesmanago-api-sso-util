<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\ContactModel;
use SALESmanago\Model\EventModel;
use SALESmanago\Model\SettingsModel;

class ContactService
{
    const
        REQUEST_METHOD_POST = 'POST',
        API_METHOD_BASIC    = '/api/contact/basic';

    private $RequestService;
    private $Settings;
    private $SettingsModel;

    public function __construct(Configuration $Settings)
    {
        $this->Settings = $Settings;
        $this->SettingsModel = new SettingsModel($Settings);
        $this->RequestService = new RequestService($Settings);
    }

    /**
     * @param Contact $Contact
     * @return array
     * @throws Exception
     */
    public function getContactBasic(Contact $Contact)
    {
        $ContactModel = new ContactModel($Contact, $this->Settings);
        $settings = $this->SettingsModel->getAuthorizationApiData();

        $contact = $ContactModel->getContactForBasicRequest();

        $data = array_merge($settings, $contact);

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD_BASIC,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(
                array_key_exists('contacts', $response),
                (count($response['contacts']) === 1)
            )
        );
    }
}
