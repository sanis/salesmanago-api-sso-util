<?php


namespace SALESmanago\Model\Collections;


class AbstractCollection implements \Iterator, \Countable , \JsonSerializable
{
    /**
     * @var array
     */
    private $collection = [];

    public function count()
    {
        // TODO: Implement count() method.
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
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

    /**
     * @param mixed;
     * @return $this;
     */
    abstract public function add($obj);
}