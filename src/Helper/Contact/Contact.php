<?php

namespace SALESmanago\Helper\Contact;

use SALESmanago\Exception\Exception;
use SALESmanago\Helper\Contact\Address;
use SALESmanago\Helper\Contact\Options;

class Contact
{
    use \SALESmanago\Helper\HelperTrait;

    const
        ASYNC   = 'async',
        CONTACT = 'contact',
        EMAIL   = 'email',
        C_ID    = 'contactId',
        FAX     = 'fax',
        NAME    = 'name',
        PHONE   = 'phone',
        COMPANY = 'company',
        STATE   = 'state',
        ADDRESS = 'address',
        EXT_ID  = 'externalId';

    public $async = true;

    private $email;
    private $contactId;
    private $fax;
    private $name;
    private $phone;
    private $company;
    private $state;
    private $externalId;

    private $Address;
    private $Options;

    private $isSubscribing = false;

    private $contact = array();

    /**
     * @param array $contactData;
     *
     * @return $this;
     * @throws Exception;
    */
    public function set($contactData) {
        if (empty($contactData)) {
            throw new Exception('Empty passed data');
        } elseif(!is_array($contactData)) {
            throw new Exception('Passed data isn\'t array() type');
        }

        foreach ($contactData as $itemName => $itemValue) {
            $methodName = 'set'.ucfirst($itemName);
            $this->$methodName($itemValue);
        }

        return $this;
    }

    /**
     * @param $param - email
     * @return $this
     */
    public function setEmail($param)
    {
        $this->email = $param;
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getEmail()
    {
    	if (isset($this->email) && !empty($this->email)) {
    		return $this->email;
	    } else {
		    throw new Exception('Empty contact email');
	    }
    }

    /**
     * Sets contact id
     * @param $param - contactId
     * @return $this
     */
    public function setContactId($param)
    {
        $this->contactId = $param;
        return $this;
    }

    /**
     * @return mixed - contactId
     */
    public function getContactId()
    {
    	return $this->contactId;
    }

    /**
     * @param $param - contact name
     * @return $this
     */
    public function setName($param)
    {
        $this->name = is_array($param)
            ? $this->setStrFromArr($param)
            : $param;
        return $this;
    }

    /**
     * @return mixed $this->name;
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $param - fax nr
     * @return $this
     */
    public function setFax($param)
    {
        $this->fax = $param;
        return $this;
    }

    /**
     * @return mixed - $this->fax;
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param $param - phone nr.
     * @return $this
     */
    public function setPhone($param)
    {
        $this->phone = $param;
        return $this;
    }

    /**
     * @return mixed $this->phone;
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $param - company name
     * @return $this
     */
    public function setCompany($param)
    {
        $this->company = $param;
        return $this;
    }

    /**
     * @return mixed $this->company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $param - state
     * @return $this
     */
    public function setState($param)
    {
        $this->state = $param;
        return $this;
    }

    /**
     * @return $this->state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param $param - external id
     * @return $this
     */
    public function setExternalId($param)
    {
        $this->externalId = $param;
        return $this;
    }

    /**
     * @return $this->externalId
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param \SALESmanago\Helper\Contact\Address $ContactAddress
     * @return $this
     */
    public function setAddress(Address $ContactAddress)
    {
        $this->Address = $ContactAddress;
        return $this;
    }

    /**
     * @return \SALESmanago\Helper\Contact\Address
     */
    public function getAddress()
    {
    	return isset($this->Address)
		    ? $this->Address
		    : $this->Address = new Address();
    }

    /**
     * @param \SALESmanago\Helper\Contact\Options $Options
     * @return $this
     */
    public function setOptions(Options $Options)
    {
        $this->Options = $Options;
        return $this;
    }

    /**
     * @return \SALESmanago\Helper\Contact\Options
     */
    public function getOptions()
    {
    	return isset($this->Options)
		    ? $this->Options
		    : $this->Options = new Options();
    }

    /**
     * @param $bool
     */
    public function setAsync($bool)
    {
	    $this->async = filter_var($bool, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Sets boolean $this->isSubscribing state of contact subscribing at that moment
     *
     * @param boolean
     * @return $this
     * */
    public function setIsSubscribingState($bool)
    {
        $this->isSubscribing = boolval($bool);
        return $this;
    }

    /**
     * Sets subscriber actual subscribing flag,
     * $this->isSubscribing - if contact subscribing at that moment;
     *
     * @return bool $this->isSubscribing
     */
    public function getIsSubscribingState()
    {
        return $this->isSubscribing;
    }

    public function get()
    {
        if (empty($this->email) && empty($this->contactId)) {
            throw new Exception('Contact identificators wasn\'t specified');
        }

        $contact = array(
        	self::ASYNC => $this->async,
            self::CONTACT =>
            array(
                self::FAX  => $this->fax,
                self::NAME => $this->setStrFromArr($this->name),
                self::PHONE   => $this->phone,
                self::COMPANY => $this->company,
                self::STATE   => $this->state,
                self::EXT_ID  => $this->externalId
                )
        );

	    if (!empty($this->email)) {
		    $contact[self::CONTACT] = array_merge(
			    $contact[self::CONTACT],
			    array(self::EMAIL => $this->email)
		    );
	    } else {
		    $contact[self::CONTACT] = array_merge(
                $contact[self::CONTACT],
                array(self::C_ID => $this->contactId)
            );
	    }

        if (!empty($this->Address)) {
            $contact[self::CONTACT] = array_merge(
                $contact[self::CONTACT],
                $this->Address->get()
            );
        }

        if (isset($this->Options)) {
            $contact = array_merge(
                $contact,
                $this->Options->get()
            );
        }

        return $this->filterData($contact);
    }

    /**
     * Sets data which is pass as array to set() method
     *
     * Array keys in parameter must by the same as SM Contact field names
     *
     * @param $contactDataArray
     * @return boolean
     */
    private function setDataFromArray($contactDataArray = array())
    {
        if (empty($contactDataArray)) {
            return false;
        }

        foreach ($contactDataArray as $itemName => $itemValue) {
            $methodName = 'set'.ucfirst($itemName);
            $this->$methodName($itemValue);
        }

        return true;
    }

    /**
     * @throws \Exception;
     * @return array $contact without async parameter;
    */
	public function getForExport()
	{
		$contact = $this->get();
		unset($contact[self::ASYNC]);

		/*fix SM service problem in batchUpsert in v2.4.12*/
		if (isset($contact[Options::TAGS])
		    && !is_array($contact[Options::TAGS])
		) {
			$contact[Options::TAGS] = explode(',', $contact[Options::TAGS]);
		}

		return $contact;
	}

    public function getDataOptions()
    {
        $data = array(
            'data' => array(),
            'options' => array()
        );

        $contact = $this->get();
        $data['data'] = $contact[self::CONTACT];
        unset($contact[self::CONTACT]);
        $data['options'] = $contact;

        return $data;
    }

    public function getForInstantUpsert()
    {
        if (empty($this->email)) {
            throw new \Exception('Contact identificators wasn\'t specified');
        }

        $contact = array(
            self::EMAIL => $this->email,
            self::NAME  => $this->setNameFromArray(),
        );

        if (isset($this->Options)) {
            $options = $this->Options->get();
            $contact = array_merge($contact, $options);
        }

        return $contact;
    }
}
