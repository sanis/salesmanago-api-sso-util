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

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function copy()
    {
        // TODO: Implement copy() method.
    }

    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function removeItem()
    {
        // TODO: Implement removeItem() method.
    }

    public function current()
    {
        // TODO: Implement current() method.
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function key()
    {
        // TODO: Implement key() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}