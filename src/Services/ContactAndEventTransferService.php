<?php


namespace SALESmanago\Services;

require_once dirname(__FILE__).'/../../vendor/autoload.php';

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;

use SALESmanago\Exception\Exception;
use SALESmanago\Model\ContactModel;
use SALESmanago\Model\EventModel;
use SALESmanago\Model\SettingsModel;

use SALESmanago\Entity\Configuration as Settings;

class ContactAndEventTransferService
{
    const
        REQUEST_METHOD_POST = 'POST',
        API_METHOD          = '/api/integration/upsert',
        CONTACT_OBJ_NAME    = 'contactUpsertRequest',
        EVENT_OBJ_NAME      = 'addContactExtEventRequest';

    private $RequestService;
    private $Settings;
    private $SettingsModel;

    public function __construct(Settings $Settings)
    {
        $this->Settings = $Settings;
        $this->SettingsModel = new SettingsModel($Settings);
        $this->RequestService = new RequestService($Settings);
    }

    /**
     * @param Contact $Contact
     * @param Event $Event
     * @return array
     * @throws Exception
     */
    public function transferBoth(Contact $Contact, Event $Event)
    {
        $ContactModel = new ContactModel($Contact, $this->Settings);
        $EventModel   = new EventModel($Event, $this->Settings);

        $settings = $this->SettingsModel->getAuthorizationApiData();
        $contact  = $ContactModel->getContactForUnionTransfer();
        $event    = $EventModel->getEventForUnionTransfer();

        $data = array_merge(
            $settings,
            [self::CONTACT_OBJ_NAME => $contact, self::EVENT_OBJ_NAME => $event]
        );

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(array_key_exists('contactId', $response), array_key_exists('eventId', $response))
        );
    }

    /**
     * @throws Exception
     * @param Event $Event
     * @return array
     */
    public function transferEvent(Event $Event)
    {
        $EventModel = new EventModel($Event, $this->Settings);

        $settings = $this->SettingsModel->getAuthorizationApiData();
        $event    = $EventModel->getEventForUnionTransfer();

        $data = array_merge(
            $settings,
            [self::EVENT_OBJ_NAME => $event]
        );

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(array_key_exists('eventId', $response))
        );
    }

    /**
     * @throws Exception
     * @param Contact $Contact
     * @return array
     */
    public function transferContact(Contact $Contact)
    {
        $ContactModel = new ContactModel($Contact, $this->Settings);

        $settings = $this->SettingsModel->getAuthorizationApiData();
        $contact  = $ContactModel->getContactForUnionTransfer();

        $data = array_merge(
            $settings,
            [self::CONTACT_OBJ_NAME => $contact]
        );

        $response = $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD,
            $data
        );

        return $this->RequestService->validateCustomResponse(
            $response,
            array(array_key_exists('contactId', $response))
        );
    }
}