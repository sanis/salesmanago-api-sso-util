<?php


namespace SALESmanago\Entity\Reporting;


class Platform
{
    /**
     * @var string platform name
     */
    private $name = 'unavailable';

    /**
     * @var string exact platform version
     */
    private $version = 'unavailable';

    /**
     * @var string exact version od integration (plugin version or app version)
     */
    private $versionOfIntegration = 'unavailable';

    /**
     * @var string url of platform where plugin or integration is installed
     */
    private $platformDomain = 'unavailable';

    /**
     * @var string PHP Version
     */
    private $phpVersion = 'unavailable';

    /**
     * @var string platform admin panel lang;
     */
    private $lang = 'unavailable';

    /**
     * @param string $param
     * @return $this
     */
    public function setName($param)
    {
        $this->name = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setVersion($param)
    {
        $this->version = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setVersionOfIntegration($param)
    {
        $this->versionOfIntegration = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersionOfIntegration()
    {
        return $this->versionOfIntegration;
    }

    /**
     * @param string $param
     * @return $this
     */
    public function setPlatformDomain($param)
    {
        $this->platformDomain = $param;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatformDomain()
    {
        return $this->platformDomain;
    }

    /**
     * @return string
     */
    public function getPhpVersion()
    {
        return phpversion();
    }

    /**
     * Set platform admin panel lang
     *
     * @param string $param
     */
    public function setLang($param)
    {
        $this->lang = $param;
        return $this;
    }

    /**
     * Return platform admin panel lang
     */
    public function getLang()
    {
        return $this->lang;
    }
}