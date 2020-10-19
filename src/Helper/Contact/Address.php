<?php

namespace SALESmanago\Helper\Contact;

use SALESmanago\Exception\Exception;

class Address
{
    use \SALESmanago\Helper\HelperTrait;

    const
        ADDRESS   = 'address',
        STREET_AD = 'streetAddress',
        ZIP_CODE  = 'zipCode',
        CITY      = 'city',
        COUNTRY   = 'country',
        PROVINCE  = 'province';

    private $streetAddress;
    private $zipCode;
    private $city;
    private $country;
    private $province;

    /**
     * @param array $data;
     *
     * @return $this;
     * @throws Exception;
     */
    public function set($data) {
        if (empty($data)) {
            throw new Exception('Empty passed data');
        } elseif(!is_array($data)) {
            throw new Exception('Passed data isn\'t array() type');
        }

        foreach ($data as $itemName => $itemValue) {
            $methodName = 'set'.ucfirst($itemName);
            $this->$methodName($itemValue);
        }

        return $this;
    }

    /**
     * @param mixed $param  - array || string
     * @return $this
     */
    public function setStreetAddress($param)
    {
        $this->streetAddress = is_array($param)
            ? $this->setStrFromArr($param)
            : $param;
        return $this;
    }

    /**
     * @return string $this->streetAddress;
     */
    public function getStreetAddress(){
        return $this->streetAddress;
    }

    /**
     * @param string $param - zipcode
     * @return $this
     */
    public function setZipCode($param)
    {
        $this->zipCode = $param;
        return $this;
    }

    /**
     * @return string $this->zipCode;
     */
    public function getZipCode(){
        return $this->zipCode;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setCity($param)
    {
        $this->city = $param;
        return $this;
    }

    /**
     * @return string $this->city;
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setCountry($param)
    {
        $this->country = $param;
        return $this;
    }

    /**
     * @return string $this->country
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * @param string $param
     * @return $this;
     */
    public function setProvince($param){
        $this->province = $param;
        return $this;
    }

    /**
     * @return string - $this->province;
     */
    public function getProvince(){
        return $this->province;
    }

    // @todo move this to another service
//    public function get()
//    {
//        return $this->filterData(array(
//            self::ADDRESS => array(
//                self::STREET_AD => $this->setStrFromArr($this->streetAddress),
//                self::ZIP_CODE  => $this->zipCode,
//                self::CITY      => $this->city,
//                self::COUNTRY   => $this->country
//            )
//        ));
//    }
}
