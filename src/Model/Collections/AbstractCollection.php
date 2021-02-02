<?php


namespace SALESmanago\Model\Collections;


abstract class AbstractCollection implements Collection
{
    /**
     * @var array
     */
    protected $collection = [];

    /**
     * @param mixed $object
     * @return AbstractCollection
     */
    abstract function addItem($object);
}