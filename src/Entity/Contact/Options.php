<?php

namespace SALESmanago\Entity\Contact;

use SALESmanago\Exception\Exception;

class Options
{
    use \SALESmanago\Entity\EntityTrait;

    const
        TAGS        = 'tags',
        R_TAGS      = 'removeTags',
        F_OPT_IN    = 'forceOptIn',
        F_OPT_OUT   = 'forceOptOut',
        F_P_OPT_IN  = 'forcePhoneOptIn',
        F_P_OPT_OUT = 'forcePhoneOptOut',
        N_EMAIL     = 'newEmail',
        CREATED_ON  = 'createdOn',
        LANG        = 'lang';

    private $birthday;
    private $forceOptIn;
    private $forceOptOut;
    private $forcePhoneOptIn;
    private $forcePhoneOptOut;
    private $properties;
    private $tags;
    private $removeTags;
    private $newEmail;
    private $createdOn;
    private $lang;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->setDataWithSetters($data);
        }
    }

    /**
     * @param array $data
     * @throws Exception
     * @return $this
     */
    public function set($data) {
        $this->setDataWithSetters($data);
        return $this;
    }

    /**
     * @param string || array - $param
     * @return $this
     */
    public function setTags($param)
    {
        $this->tags = is_array($param)
            ? $this->setStrFromArr($param, ',')
            : $param;
        return $this;
    }

    /**
     * @return string $this->tags
     */
    public function getTags()
    {
    	return $this->tags;
    }

    /**
     * @param string || array - $param
     * @return $this
     */
    public function setRemoveTags($param)
    {
        $this->removeTags = is_array($param)
            ? $this->setStrFromArr($param, ',')
            : $param;
        return $this;
    }

    /**
     * @return string $this->removeTags
     */
    public function getRemoveTags()
    {
        return $this->removeTags;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setForceOptIn($param)
    {
        $this->forceOptIn = boolval($param);
        $this->setForceOptOut(!boolval($param));

        return $this;
    }

    /**
     * @return bool $this->forceOptIn
     */
	public function getForceOptIn()
	{
		return $this->forceOptIn;
	}

    /**
     * @param bool $param
     * @return $this
     */
    public function setForceOptOut($param)
    {
        $this->forceOptOut = $param;
        return $this;
    }

    /**
     * @return bool $this->forceOptOut
     */
    public function getForceOptOut(){
        return $this->forceOptOut;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setForcePhoneOptIn($param)
    {
        $this->forcePhoneOptIn = boolval($param);
        $this->setForcePhoneOptOut(!boolval($param));
        return $this;
    }

    /**
     * @return bool $this->forcePhoneOptIn
     */
    public function getForcePhoneOptIn()
    {
        return $this->forcePhoneOptIn;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setForcePhoneOptOut($param)
    {
        $this->forcePhoneOptOut = $param;
        return $this;
    }

    /**
     * @return bool $this->forcePhoneOptOut
     */
    public function getForcePhoneOptOut()
    {
        return $this->forcePhoneOptOut;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setNewEmail($param)
    {
        $this->newEmail = $param;
        return $this;
    }

    /**
     * @return string $this->newEmail
     */
    public function getNewEmail()
    {
        return $this->newEmail;
    }

    /**
     * @param mixed $param
     * @return $this
     */
    public function setCreatedOn($param)
    {
        $this->createdOn = $param;
        return $this;
    }

    /**
     * @return $this->createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setLang($param)
    {
        $this->lang = $param;
        return $this;
    }

    public function getLang(){
        return $this->lang;
    }
}
