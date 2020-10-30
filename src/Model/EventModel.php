<?php


namespace SALESmanago\Model;

use SALESmanago\Entity\Contact\Options;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Entity\Configuration as Settings;
use SALESmanago\Helper\DataHelper;

class EventModel
{
    protected $Event;
    protected $Settings;

    public function __construct(Event $Event, Settings $Settings)
    {
        $this->Event = $Event;
        $this->Settings = $Settings;
    }

    public function getEventForUnionTransfer()
    {
        $eventRequestArray = [
            Settings::CLIENT_ID  => $this->Settings->getClientId(),
            Settings::OWNER      => $this->Settings->getOwner(),
            Options::F_OPT_IN    => $this->Event->getForceOptIn(),
            Event::EMAIL         => $this->Event->getEmail(),
            Event::CONTACT_ID    => $this->Event->getContactId(),
            Event::CONTACT_EVENT => [
                Event::EVENT_ID       => $this->Event->getEventId(),
                Event::DATE           => $this->Event->getDate(),
                Event::DESCRIPTION    => $this->Event->getDescription(),
                Event::PRODUCTS       => $this->Event->getProducts(),
                Event::LOCATION       => $this->Event->getLocation(),
                Event::VALUE          => $this->Event->getValue(),
                Event::EXT_EVENT_TYPE => $this->Event->getContactExtEventType(),
                Event::EXT_ID         => $this->Event->getExternalId(),
                Event::SHOP_DOMAIN    => $this->Event->getShopDomain()
            ]
        ];

        $eventRequestArray[Event::CONTACT_EVENT] = array_merge($eventRequestArray[Event::CONTACT_EVENT], $this->getEventDetailsRequestArray());
        return DataHelper::filterDataArray($eventRequestArray);
    }

    /**
     * Get evnt details from Entity & set event detail array keys for request
     * @return array of event details
     */
    protected function getEventDetailsRequestArray()
    {
        $eventDetails = $this->Event->getDetails();

        if (empty($eventDetails)) {
            return $eventDetails;
        }

        array_unshift($eventDetails,"");//this will hel to set array from 1 (detail1, detail2...) (not detail0)
        unset($eventDetails[0]);

        foreach ($eventDetails as $key => $detail) {
            $eventDetails[Event::DETAIL.$key] = $detail;
            unset($eventDetails[$key]);
        }

        return $eventDetails;
    }
}
