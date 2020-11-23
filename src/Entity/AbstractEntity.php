<?php


namespace SALESmanago\Entity;


use SALESmanago\Exception\Exception;

class AbstractEntity
{
    /**
     * @param array $data
     * @throws Exception
     */
    protected function setDataWithSetters($data)
    {
        if (empty($data)) {
            throw new Exception('Empty passed data');
        } elseif(!is_array($data)) {
            throw new Exception('Passed data isn\'t array() type');
        }

        foreach ($data as $itemName => $itemValue) {
            $methodName = 'set'.ucfirst($itemName);

            if (!method_exists($this, $methodName)) {
                throw new Exception("Set method :: {$methodName} - doesn't exist");
            }

            $this->$methodName($itemValue);
        }
    }
}