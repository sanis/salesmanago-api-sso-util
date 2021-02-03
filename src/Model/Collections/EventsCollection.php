<?php


namespace SALESmanago\Model\Collections;

use SALESmanago\Entity\Event\Event;
use SALESmanago\Exception\Exception;

class EventsCollection extends AbstractCollection
{
    /**
     * @throws Exception
     * @param Event $object
     * @return AbstractCollection|void
     */
    public function addItem($object)
    {
        if(!($object instanceof Event)) {
            throw new Exception('Not right entity type');
        }

        array_push($this->collection, $object);
        return $this;
    }
}