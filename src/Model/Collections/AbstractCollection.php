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

    /**
     * @return array of objects
     */
    public function getItems()
    {
        return $this->collection;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !boolval($this->count());
    }

    /**
     * Clear current collection
     * @return $this;
     */
    public function clear()
    {
        $this->collection = [];
        return $this;
    }

    /**
     * @return Collection of collection
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Parse Collection to array
     * @return array
     */
    abstract function toArray();

    /**
     * Remove Item;
     * @param int $key
     * @return $this;
     */
    public function removeItem($key)
    {
        unset($this->collection[$key]);
        return $this;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return next($this->collection);
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->collection);
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}