<?php


namespace SALESmanago\Model\Collections;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\ContactModel;

class ContactsCollection extends AbstractCollection
{
    /**
     * @throws Exception
     * @param Contact $object
     * @return AbstractCollection|void
     */
    public function addItem($object)
    {
        if(!($object instanceof Contact)) {
            throw new Exception('Not right entity type');
        }

        array_push($this->collection, $object);
        return $this;
    }

    /**
     * Parse Collection to array
     * @return array
     */
    public function toArray()
    {
        $contacts = [];

        if (!$this->isEmpty()) {
            array_walk($this->collection, function ($contact, $key) use (&$events) {
                array_push($events, ContactModel::toArray($contact, Configuration::getInstance()));
            });
        }

        return ['events' => $contacts];
    }
}