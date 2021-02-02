<?php


namespace SALESmanago\Model\Collections;


interface Collection extends \Iterator, \Countable, \JsonSerializable
{
    /**
     * Clear current collection
     * @return bool
     */
    public function clear();

    /**
     * @return Collection of collection
     */
    public function copy();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Parse Collection to array
     * @return array
     */
    public function toArray();

    /**
     * @param mixed $object;
     * @return $this;
     */
    public function addItem($object);

    /**
     * Remove Item;
     * @return $this;
     */
    public function removeItem();
}