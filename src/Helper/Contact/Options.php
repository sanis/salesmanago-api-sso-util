<?php

namespace Benhauer\Salesmanago\Helper\Contact;

use \Magento\Framework\App\Helper\AbstractHelper;

class Options extends AbstractHelper
{
    const
        BIRTHDAY    = 'birthday',

        TAGS        = 'tags',
        PROPERTIES  = 'properties',
        R_TAGS      = 'removeTags',

        F_OPT_IN    = 'forceOptIn',
        F_OPT_OUT   = 'forceOptOut',

        F_P_OPT_INT = 'forcePhoneOptIn',
        F_P_OPT_OUT = 'forcePhoneOptOut',
        N_EMAIL     = 'newEmail',

        CREATED_ON  = 'createdOn',

        U_API_D_OPT_IN        = 'useApiDoubleOptIn',
	    D_OPT_IN_TEMPLATE_ID  = 'apiDoubleOptInEmailTemplateId',
        D_OPT_IN_EMAIL_ACC_ID = 'apiDoubleOptInEmailAccountId',
        D_OPT_IN_EMAIL_SUBJ   = 'apiDoubleOptInEmailSubject';

    public $birthday;
    public $forceOptIn;
    public $forceOptOut;
    public $forcePhoneOptIn;
    public $forcePhoneOptOut;

    public $properties;

    public $tags;
    public $removeTags;

    public $newEmail;

    public $additional = array();

    public $useApiDoubleOptIn = array(
    	self::U_API_D_OPT_IN        => false,
	    self::D_OPT_IN_TEMPLATE_ID  => '',
	    self::D_OPT_IN_EMAIL_ACC_ID => '',
	    self::D_OPT_IN_EMAIL_SUBJ   => ''
    );

    /**
     * @param array $tags;
     * @param array $removeTags;
     * @param array $birthday;
     * @param boolean $forceOptIn;
     * @param boolean $forceOptOut;
     * @param boolean $forcePhoneOptIn;
     * @param boolean $forcePhoneOptOut;
     * @param array $properties;
     * @param string $newEmail;
    */
    public function set(
        $tags = array(),
        $removeTags = array(),
        $birthday = '',
        $forceOptIn = '',
        $forceOptOut = '',
        $forcePhoneOptIn = '',
        $forcePhoneOptOut = '',
        $properties = array(),
        $newEmail = ''
    ) {
        $this->tags             = $tags;
        $this->removeTags       = $removeTags;
        $this->birthday         = $birthday;
        $this->forceOptIn       = $forceOptIn;
        $this->forceOptOut      = $forceOptOut;
        $this->forcePhoneOptIn  = $forcePhoneOptIn;
        $this->forcePhoneOptOut = $forcePhoneOptOut;
        $this->properties       = $properties;
        $this->newEmail         = $newEmail;
        return $this;
    }

    public function setTags($param)
    {
        $this->tags = $param;
        return $this;
    }

    public function getTags()
    {
    	return $this->tags;
    }

    public function setBirthday($param)
    {
        $this->birthday = $param;
        return $this;
    }

    public function setForceOptIn($param)
    {
        $this->forceOptIn = $param;
        return $this;
    }

	public function getForceOptIn()
	{
		return $this->forceOptIn;
	}

    public function setForceOptOut($param)
    {
        $this->forceOptOut = $param;
        return $this;
    }

    public function setForcePhoneOptIn($param)
    {
        $this->forcePhoneOptIn = $param;
        return $this;
    }

    public function setForcePhoneOptOut($param)
    {
        $this->forcePhoneOptOut = $param;
        return $this;
    }

    public function setProperties($param)
    {
        $this->properties = $param;
        return $this;
    }

    public function setNewEmail($param)
    {
        $this->newEmail = $param;
        return $this;
    }

    /**
     * Sets additional options such syncrule createOn etc.
     * @param array $params;
    */
    public function setAdditional($params)
    {
        $this->additional = array_merge($this->additional, $params);
        return $this;
    }

	/**
	 * @param array - array(
	 *                      'useApiDoubleOptIn' => true/false,
	 *                      'apiDoubleOptInEmailTemplateId' => '',
	 *                      'apiDoubleOptInEmailAccountId' => '',
	 *                      'apiDoubleOptInEmailSubject' => ''
	 *                )
	 * @return $this;
	 */
    public function setUseApiDoubleOptIn($params)
    {
		if(empty($params)
		   || !isset($params[self::U_API_D_OPT_IN])
		   || !$params['useApiDoubleOptIn']
		) {
			return $this;
		}

		$this->useApiDoubleOptIn[self::U_API_D_OPT_IN] = $params[self::U_API_D_OPT_IN];

		if (isset($params[self::D_OPT_IN_TEMPLATE_ID])) {
			$this->useApiDoubleOptIn[self::D_OPT_IN_TEMPLATE_ID] = $params[self::D_OPT_IN_TEMPLATE_ID];
		}

		if (isset($params[self::D_OPT_IN_EMAIL_ACC_ID])) {
			$this->useApiDoubleOptIn[self::D_OPT_IN_EMAIL_ACC_ID] = $params[self::D_OPT_IN_EMAIL_ACC_ID];
		}

		if (isset($params[self::D_OPT_IN_EMAIL_SUBJ])) {
			$this->useApiDoubleOptIn[self::D_OPT_IN_EMAIL_SUBJ] = $params[self::D_OPT_IN_EMAIL_SUBJ];
		}

		return $this;
    }

	/**
	 * @return array
	 */
	public function getUseApiDoubleOptIn() {
		return $this->useApiDoubleOptIn;
	}

	/**
     * @return array - firltered array of sm contact options;
	*/
    public function get()
    {
        $this->setOptStatusesBaseOnForceOptIn();
        $options = array(
            self::TAGS        => isset($this->tags) ? $this->setStrFromArr($this->tags, ',') : '',
            self::R_TAGS      => isset($this->tags) ? $this->setStrFromArr($this->removeTags, ',') : '',
            self::N_EMAIL     => isset($this->newEmail) ? $this->newEmail : '',
            self::PROPERTIES  => isset($this->properties) ? $this->properties : '',
            self::F_OPT_IN    => isset($this->forceOptIn) ? $this->forceOptIn : false,
            self::F_OPT_OUT   => isset($this->forceOptOut) ? $this->forceOptOut : false,
            self::F_P_OPT_INT => isset($this->forcePhoneOptIn) ? $this->forcePhoneOptIn : false,
            self::F_P_OPT_OUT => isset($this->forcePhoneOptOut) ? $this->forcePhoneOptOut : false,
            self::BIRTHDAY    => isset($this->birthday) ? $this->birthday : '',
            self::CREATED_ON  => isset($this->createdOn) ? $this->createdOn : ''
        );

        if ($this->useApiDoubleOptIn[self::U_API_D_OPT_IN]) {
	        $options = array_merge($this->useApiDoubleOptIn, $options);
        }

        if (isset($this->additional) && !empty($this->additional)) {
            $options = array_merge($options, $this->additional);
        }

        return $this->filterData($options);
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

    protected function setStrFromArr($param, $glue = ' ')
    {
        if (!is_array($param)) {
            return $param;
        }

        $param = $this->filterArr($param);
        if (!empty($param)) {
            return implode($glue, $param);
        }

        return '';
    }

    protected function setOptStatusesBaseOnForceOptIn()
    {
        if (is_bool($this->forceOptIn)
            && $this->forceOptOut != ''
        ) {
            $this->forceOptOut = !$this->forceOptIn;
            $this->forcePhoneOptIn = $this->forceOptIn;
            $this->forcePhoneOptOut = !$this->forceOptIn;
        }
    }

    protected function filterData($data)
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
