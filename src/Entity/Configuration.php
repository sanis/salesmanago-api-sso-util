<?php


namespace SALESmanago\Entity;


use SALESmanago\Entity\ApiDoubleOptIn;
use SALESmanago\Exception\Exception;

class Configuration extends AbstractEntity
{
    const
        ACTIVE        = 'active',
        ENDPOINT      = 'endpoint',
        CLIENT_ID     = 'clientId',
        API_KEY       = 'apiKey',
        API_SECRET    = 'apiSecret',
        OWNER         = 'owner',
        EMAIL         = 'email',
        SHA           = 'sha',
        TOKEN         = 'token',
        IGNORE_DOMAIN = 'ignoreDomain',
        COOKIE_TTL    = 'cookieTtl';

    private static $instances = [];

    /**
     * Flag to detect if module/component/plugin is active on platform;
     * @var boolean
     */
    protected $active = false;

    /**
     * @var string|null
     */
    protected $endpoint = 'https://app2.salesmanago.pl';

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @var string
     */
    protected $owner;

    /**
     * @var string
     */
    protected $sha;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var array
     */
    protected $removeTags;

    /**
     * @var array
     */
    protected $ignoreDomain;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $domainToken;

    /**
     * @var array
     */
    protected $consentDetails;

    /**
     * @var ApiDoubleOptIn
     */
    protected $ApiDoubleOptIn;

    /**
     * @var bool active state for Synchronization Service;
     */
    protected $activeSynchronization = false;

    /**
     * @var bool Shows if synchronization needed for particular entity (Contact)
     */
    protected $requireSynchronization = false;

    /**
     * @var int Time in seconds for Cookie expire Time
     */
    protected $cookieTtl = 43200;

    /**
     * @var array|null - owners list for SM account
     */
    protected $ownersList = null;

    /**
     * @var string unique store id for SALESmanago (used with events, feed)
     */
    protected $location;

    /**
     * Configuration schema version used to specify
     * which settings version is used by client;
     *
     * @var string - version
     */
    protected $confSchemaVer = '1.0.0';

    protected function __construct() {}
    protected function __clone() {}

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * @return mixed|static
     */
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
     * Sets data from array
     * @param $data
     * @return $this;
     * @throws Exception
     */
    public function set($data)
    {
        $this->setDataWithSetters($data);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     * @param boolean $ssl
     * @return $this
     */
    public function setEndpoint($endpoint, $ssl = true)
    {
        if (preg_match('/^https?:\/\//', $endpoint)) {
            if ($ssl === true && preg_match("@^(?:http:\/\/)([^/]+)@i", $endpoint, $matches)) {
                $this->endpoint = "https://" . $matches[1];
            } else {
                $this->endpoint = $endpoint;
            }
        } else {
            if ($ssl === true) {
                $this->endpoint = "https://" . $endpoint;
            } else {
                $this->endpoint = "http://" . $endpoint;
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        if (empty($this->apiKey)) {
            $this->apiKey = md5(time() . mt_rand());
        }

        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultApiKey()
    {
        $this->apiKey = md5(time() . mt_rand());
        return $this;
    }

    /**
     * @return string
     */
    protected function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     * @return $this
     */
    public function setApiSecret($apiSecret)
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    /**
     * @param string $sha
     * @return $this
     */
    public function setSha($sha)
    {
        $this->sha = $sha;
        return $this;
    }

    public function generateSha()
    {
        $this->sha = sha1($this->getApiKey() . $this->getClientId() . $this->getApiSecret());
        return $this;
    }

    /**
     * @return string
     */
    public function getSha()
    {
        if (empty($this->sha)) {
            self::generateSha();
        }
        return $this->sha;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $tags = explode(',', $tags);
        foreach ($tags as $key => $tag) {
            $tags[$key] = strtoupper(trim($tag));
        }
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param string $removeTags
     * @return $this
     */
    public function setRemoveTags($removeTags)
    {
        $removeTags = explode(',', $removeTags);
        foreach ($removeTags as $key => $tag) {
            $removeTags[$key] = strtoupper(trim($tag));
        }
        $this->removeTags = $removeTags;
        return $this;
    }

    /**
     * @return array
     */
    public function getRemoveTags()
    {
        return $this->removeTags;
    }

    /**
     * @param array $ignoreDomain
     * @return $this
     * @throws Exception
     */
    public function setIgnoreDomain($ignoreDomain)
    {
        if(empty($ignoreDomain))
            return $this;
        if (!is_array($ignoreDomain)) {
            throw new Exception('Passed argument isn\'t array');
        }
        $this->ignoreDomain = $ignoreDomain;
        return $this;
    }

    /**
     * @return array
     */
    public function getIgnoreDomain()
    {
        return $this->ignoreDomain;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domainToken
     * @return $this
     */
    public function setDomainToken($domainToken)
    {
        $this->domainToken = $domainToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomainToken()
    {
        return $this->domainToken;
    }

    /**
     * @param  array $params
     * @return int
     */
    public function count($params)
    {
        return !is_null($params) && is_array($params) ? count($params) : 0;
    }

    public function getUserIP() {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = 'UNKNOWN';

        $ips = explode(",",$ip);
        if(is_array($ips)) {
            return trim($ips[0]);
        }

        return $ip;
    }

    /**
     * @param mixed $param  //ApiDoubleOptIn object is preferred
     * @return $this
     */
    public function setApiDoubleOptIn($param)
    {
        if (is_array($param)) {
            $this->ApiDoubleOptIn = new ApiDoubleOptIn($param);
        } else if ($param instanceof ApiDoubleOptIn) {
            $this->ApiDoubleOptIn = $param;
        }

        return $this;
    }

    /**
     * @return ApiDoubleOptIn
     */
    public function getApiDoubleOptIn()
    {
        return isset($this->ApiDoubleOptIn) ? $this->ApiDoubleOptIn : new ApiDoubleOptIn();
    }

    /**
     * @param bool $param
     * @return $this
     */
    public function setActiveSynchronization($param)
    {
        $this->activeSynchronization = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActiveSynchronization()
    {
        return $this->activeSynchronization;
    }

    /**
     * @param bool $param
     * @return $this;
     */
    public function setRequireSynchronization($param)
    {
        $this->requireSynchronization = $param;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequireSynchronization()
    {
        return $this->requireSynchronization;
    }

    /**
     * @param int $param
     * @return $this;
     */
    public function setCookieTtl($param)
    {
        $this->cookieTtl = $param;
        return $this;
    }

    /**
     * @return int
     */
    public function getCookieTtl()
    {
        return $this->cookieTtl;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setConfSchemaVer($param)
    {
        $this->confSchemaVer = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfSchemaVer()
    {
        return $this->confSchemaVer;
    }

    /**
     * @param $param
     * @return $this
     */
    public function setOwnersList($param)
    {
        $this->ownersList = $param;
        return $this;
    }

    /**
     * @return array
     */
    public function getOwnersList()
    {
        return $this->ownersList;
    }

    /**
     * @param $param
     * @return $this
     */
    public function setLocation($param)
    {
        $this->location = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }
}
