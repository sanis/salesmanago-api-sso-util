<?php

namespace SALESmanago\Entity\Contact;

use SALESmanago\Entity\AbstractEntity;
use SALESmanago\Exception\Exception;
use SALESmanago\Helper\EntityDataHelper;

class Options extends AbstractEntity
{
    const
        ASYNC        = 'async',
        TAGS         = 'tags',
        R_TAGS       = 'removeTags',
        TAGS_SCORING = 'tagScoring',
        F_OPT_IN     = 'forceOptIn',
        F_OPT_OUT    = 'forceOptOut',
        F_P_OPT_IN   = 'forcePhoneOptIn',
        F_P_OPT_OUT  = 'forcePhoneOptOut',
        N_EMAIL      = 'newEmail',
        CREATED_ON   = 'createdOn',
        LANG         = 'lang';

    /**
     * @var bool - flag to send contact asynchronously (true) or not (false)
     */
    private $async            = true;

    /**
     * @var bool - email optin state
     */
    private $forceOptIn       = false;

    /**
     * @var bool - email optout state
     */
    private $forceOptOut      = false;

    /**
     * @var bool - phone optin state
     */
    private $forcePhoneOptIn  = false;

    /**
     * @var bool - phone optout state
     */
    private $forcePhoneOptOut = false;

    /**
     * @var bool - enable (true) or disable (false) tag scoring
     */
    private $tagScoring       = false;

    /**
     * @var array - contact tags
     */
    private $tags             = [];

    /**
     * @var array - contact tags to remove
     */
    private $removeTags       = [];

    /**
     * @var null|string - new contact email; use in case of email change
     */
    private $newEmail         = null;

    /**
     * @var null|string - contact creates at time;
     */
    private $createdOn        = null;

    /**
     * @var null|string - contact language
     */
    private $lang             = null;

    /**
     * Flag for contact subscriptions
     * Set this flag if contact subscribes at the current moment
     * @var bool
     */
    private $isSubscribes     = false;

    /**
     * Flag fir contact subscription state
     * Shows if contact already subscribed
     *
     * @var bool
     */
    private $isSubscribed = false;

    /**
     * Flag for contact unsubscribe
     * Set this flag if contact unsubscribes
     * @var bool
     */
    private $isUnSubscribes   = false;

    /**
     * Flag for contact subscription status no change;
     * This flag is used by default;
     * This mean that existing contact in SALESmanago must not change his optin status
     * or set optOut status while contact doesn exist;
     * @var bool
     */
    private $isSubscriptionStatusNoChange = true;

    private $optedOut      = null;
    private $optedOutPhone = null;
    private $deleted       = false;
    private $invalid       = false;
    private $modifiedOn    = null;
    private $lastVisit     = null;

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
     * @param bool $bool
     * @return $this
     */
    public function setAsync($bool)
    {
        $this->async = filter_var($bool, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }

    /**
     * @return bool
     */
    public function getAsync()
    {
        return $this->async;
    }

    public function setTagScoring($param)
    {
        $this->tagScoring = $param;
        return $this;
    }

    public function getTagScoring()
    {
        return $this->tagScoring;
    }

    /**
     * @param array | string $param
     * @return $this
     */
    public function setTags($param)
    {
        if (is_array($param)) {
            array_walk($param, function($item) {
                $this->setTags($item);
            });
        }

        if (is_string($param) && !empty($param)) {
            $tags = strtoupper(str_replace(' ', '_', trim($param)));
            $tags = ((strpos($tags, ',') !== false)) ? explode(',',  $tags) : $tags;

            $this->tags = is_array($tags) ? array_merge($this->tags, $tags) : array_merge($this->tags, [$tags]);
        }

        return $this;
    }


    /**
     * @param string $param
     * @return $this
     */
    public function appendTag($param)
    {
        if(!empty($param) && is_string($param)) {
            $this->tags[] = strtoupper(str_replace(' ', '_', trim($param)));
        }
        return $this;
    }

    /**
     * @param array | String $param
     * @return $this
     */
    public function appendTags($param)
    {
        /* if $param is array */
        if (is_array($param)) {
            array_walk($param, function ($item) {
                strtoupper(str_replace(' ', '_', trim($item)));
            });
            $this->tags = array_merge($this->tags, $param);

        /* if $param is string with multiple tags */
        } elseif (is_string($param) && strpos($param, ',') !== false) {
            $tags = explode(',',  $param);
            array_walk($tags, function ($item) {
                strtoupper(str_replace(' ', '_', trim($item)));
            });
            $this->tags = array_merge($this->tags, $tags);

        /* if $param is a single tag */
        } elseif (is_string($param)) {
            $param = strtoupper(str_replace(' ', '_', trim($param)));
            $this->tags[] = $param;
        }
        return $this;
    }

    /**
     * @return array $this->tags
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
        if (is_array($param)) {
            array_walk($param, function($item) {strtoupper(str_replace(' ', '_', $item));});
            $this->removeTags = EntityDataHelper::filterDataArray($param);
        } else {
            $this->removeTags = [strtoupper(str_replace(' ', '_', $param))];
        }

        return $this;
    }

    /**
     * @return array $this->removeTags
     */
    public function getRemoveTags()
    {
        return $this->removeTags;
    }

    /**
     * Sets boolean $this->isSubscribing state of contact subscribing at that moment
     *
     * @param boolean $param
     * @return $this
     * */
    public function setIsSubscribes($param)
    {
        $this->isSubscribes = boolval($param);
        return $this;
    }

    /**
     * Get subscriber actual subscribing flag,
     * $this->isSubscribes - if contact is subscribing at that moment;
     *
     * @return bool $this->isSubscribes
     */
    public function getIsSubscribes()
    {
        return $this->isSubscribes;
    }

    /**
     * Sets boolean $this->isSubscribed sate of contact subscribed at platform
     * !Use Only for exports
     * @param boolean $param
     * @return $this
     * */
    public function setIsSubscribed($param)
    {
        $this->isSubscribed = boolval($param);
        return $this;
    }

    /**
     * Get subscriber actual subscribed flag,
     * !Use Only for exports
     * $this->isSubscribed - if contact is subscribed at platform;
     *
     * @return bool $this->isSubscribed
     */
    public function getIsSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * Sets subscriber actual unsubscribing flag,
     * @param bool $param - if contact is unsubscribing at that moment;
     * @return $this
     */
    public function setIsUnSubscribes($param)
    {
        $this->isUnSubscribes = $param;
        return $this;
    }

    /**

     * @return bool
     */
    public function getIsUnSubscribes()
    {
        return $this->isUnSubscribes;
    }

    /**
     * Flag wich must be used when contact status must be no changed after pushing him to SM
     * @param bool $param
     * @return $this
     */
    public function setIsSubscriptionStatusNoChange($param){
        $this->isSubscriptionStatusNoChange = $param;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsSubscriptionStatusNoChange() {
        return $this->isSubscriptionStatusNoChange;
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

    /**
     * @param bool $param
     * @return $this
     */
    public function setOptedOut($param)
    {
        $this->optedOut = $param;
        return $this;
    }

    /**
     * @return null|bool
     */
    public function getOptedOut()
    {
        return $this->optedOut;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setOptedOutPhone($param)
    {
        $this->optedOutPhone = $param;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getOptedOutPhone()
    {
        return $this->optedOutPhone;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setDeleted($param)
    {
        $this->deleted = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setInvalid($param)
    {
        $this->invalid = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function getInvalid()
    {
        return $this->invalid;
    }

    /**
     * @return $this
     */
    public function setModifiedOn($param)
    {
        $this->modifiedOn = $param;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifiedOn()
    {
        return $this->modifiedOn;
    }

    /**
     * @param $param
     * @return $this
     */
    public function setLastVisit($param)
    {
        $this->lastVisit = $param;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }
}
