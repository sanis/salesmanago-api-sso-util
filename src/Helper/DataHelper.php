<?php


namespace SALESmanago\Helper;


class DataHelper
{
    /**
     * Unset empty array values
     *
     * @param array $data
     * @return array
     */
    public static function filterDataArray($data)
    {
        $data = array_map(function ($var) {
            return is_array($var) ? $this->filterData($var) : $var;
        }, $data);
        $data = array_filter($data, function ($value) {
            return !empty($value) || $value === false;
        });
        return $data;
    }
}