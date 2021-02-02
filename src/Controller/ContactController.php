<?php


namespace SALESmanago\Controller;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\SalesManagoException;
use SALESmanago\Model\ContactModel;
use SALESmanago\Services\ContactService;

class ContactController
{
    protected $settings;
    protected $service;

    public function __construct(Configuration $settings)
    {
        $this->settings = $settings;
        $this->service  = new ContactService($settings);
    }

    /**
     * @param Contact $Contact
     * @return Contact|null
     * @throws \SALESmanago\Exception\Exception
     */
    public function getContactBasic(Contact $Contact)
    {
        $response = $this->service->getContactBasic($Contact);
        $contactModel = new ContactModel($Contact, $this->settings);

        if ($response['success'] && !empty($response['contacts'][0])) {
            return $contactModel->getContactFromBasicResponse($response['contacts'][0]);
        }

        return null;
    }
}
