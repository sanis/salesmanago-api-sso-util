<?php


namespace SALESmanago\Model\Collections;


use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\Exception;

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
}