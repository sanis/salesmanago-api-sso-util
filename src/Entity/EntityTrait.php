<?php

namespace SALESmanago\Entity;

use SALESmanago\Exception\Exception;

trait EntityTrait
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

    protected function setStrFromArr($param, $glue = ' ')
    {
        if (!is_array($param)) {
            return $param;
        }

        $param = $this->filterArr($param);
        if (!empty($param)) {
            return trim(implode($glue, $param));
        }

        return '';
    }

    protected function filterArr($array)
    {
        return array_filter(
            $array,
            function ($value) {
                return !empty($value);
            }
        );
    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isTimestamp($string)
    {
        if (!$string) {
            return false;
        }

        try {
            new \DateTime($string);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param mixed $param - int || string
     * @return bool
     */
    protected function isUnixTime($param)
    {
        $answer = ((intval($param)) == $param)
            && (intval($param) <= PHP_INT_MAX)
            && (intval($param) >= ~PHP_INT_MAX);

        if(is_string($param)){
            $answer = ($answer && ((1 === preg_match( '~^[1-9][0-9]*$~', $param))));
        }

        return $answer;
    }
}