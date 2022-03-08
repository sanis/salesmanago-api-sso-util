<?php


namespace SALESmanago\Model\Report;

use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Reporting\Platform;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Contact\Address;
use SALESmanago\Entity\Contact\Options;
use SALESmanago\Entity\Event\Event;

class ReportModel
{
    const
        ACT_LOGOUT          = 'logout',
        ACT_LOGIN           = 'login',
        ACT_EXPORT          = 'export',
        ACT_EXPORT_EVENT    = 'export',
        ACT_EXPORT_CONTACTS = 'export',
        ACT_EXCEPTION       = 'exception',
        ACT_UPDATE          = 'update',
        ACT_DEACTIVATION    = 'deactivation',
        ACT_DELETE          = 'delete',
        ACT_UNKNOWN         = 'unknown',
        //use only for tests:
        ACT_TEST            = 'test';

    /**
     * @var string[] - mapping actual action to event type in SALESmanago
     */
    protected $actionToEventType = [
        //client actions:
        self::ACT_LOGOUT       => 'RETURN',
        self::ACT_LOGIN        => 'LOGIN',
        self::ACT_EXPORT       => 'TRANSACTION',
        //integration/plugin action:
        self::ACT_EXCEPTION    => 'CANCELLATION',
        self::ACT_UPDATE       => 'ACTIVATION',
        self::ACT_DEACTIVATION => 'CANCELLATION',
        self::ACT_DELETE       => 'APP_TYPE_RETENTION',
        self::ACT_UNKNOWN      => 'OTHER',
        self::ACT_TEST         => 'SURVEY'
    ];

    /**
     * @var ConfigurationInterface
     */
    private $conf;

    /**
     * @var Platform
     */
    private $platform;

    /**
     * ReportModel constructor.
     *
     * @param ConfigurationInterface $conf
     * @param Platform $platform
     */
    public function __construct(ConfigurationInterface $conf, Platform $platform)
    {
        $this->conf = $conf;
        $this->platform = $platform;
    }

    /**
     * @return Contact return new instance of contact
     */
    private function getContact()
    {
        return new Contact();
    }

    /**
     * @return Event return new instance of Event
     */
    private function getEvent()
    {
        return new Event();
    }

    /**
     * Generate client information for reporting service
     *
     * @param string $actionType - action tag
     * @return Contact
     */
    public function getClientAsContact($actionType = self::ACT_UNKNOWN)
    {
        $Contact = $this->getContact()
            ->setEmail($this->conf->getOwner())
            ->setName($this->platform->getPlatformDomain())
            ->setExternalId($this->conf->getClientId())
            ->setCompany($this->platform->getName());

        $Contact->setAddress(
            $Contact->getAddress()
                ->setStreetAddress($this->platform->getVersionOfIntegration())
                ->setCountry($this->platform->getLang())
        );

        $Contact->setOptions(
            $Contact->getOptions()
                ->setLang($this->platform->getLang())
                ->appendTags(
                    [
                        $this->platform->getName(),
                        $this->platform->getVersion(),
                        $this->platform->getVersionOfIntegration(),
                        'PHP:' . $this->platform->getPhpVersion(),
                        $this->actionToEventType[$actionType]
                    ]
                )
        );

        return $Contact;
    }

    /**
     * Generate event information for reporting service
     *
     * @param string $actionType one of const
     * @param array $arr
     * @return Event
     */
    public function getActionAsEvent($actionType = self::ACT_UNKNOWN, $arr = [])
    {
        $Event = $this->getEvent()
            ->setEmail($this->conf->getOwner())
            ->setProducts($this->platform->getVersion())
            ->setLocation($this->platform->getVersionOfIntegration())
            ->setDetail('PHP:' . $this->platform->getPhpVersion(), 1)
            ->setDetail('Date:' . time())
            ->setContactExtEventType($this->actionToEventType[$actionType]);

        if (!empty($arr)) {
            $Event->setDescription(json_encode($arr));
        }

        return $Event;
    }
}