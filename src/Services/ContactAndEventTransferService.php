<?php


namespace SALESmanago\Services;

use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Event\Event;

use SALESmanago\Exception\Exception;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Model\ContactModel;
use SALESmanago\Model\EventModel;
use SALESmanago\Model\SettingsModel;

use SALESmanago\Entity\Settings;
use SALESmanago\Services\RequestService;

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

    public function transferContact(Contact $Contact)
    {
        try {
            $ContactModel = new ContactModel($Contact, $this->Settings);

            $settings = $this->SettingsModel->getAuthorizationApiData();
            $contact = $ContactModel->getContactForUnionTransfer();

            $data = array_merge($settings, [self::CONTACT_OBJ_NAME => $contact]);

            $response = $this->RequestService->request(
                self::REQUEST_METHOD_POST,
                self::API_METHOD,
                $data
            );

            return $this->RequestService->validateCustomResponse(
                $response,
                array(array_key_exists('contactId', $response))
            );
        } catch (Exception $e) {

        } catch (SalesManagoException $e) {

        }
    }

    public function transferEvent(Event $Event)
    {
        $EventModel = new EventModel($Event, $this->Settings);
    }

    public function transferBoth(Contact $Contact, Event $Event)
    {
        $ContactModel = new ContactModel($Contact, $this->Settings);
        $EventModel = new EventModel($Event, $this->Settings);
    }
}