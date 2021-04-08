<?php


namespace SALESmanago\Entity;


interface ReportConfigurationInterface
{
    /**
     * @param $param
     * @return mixed
     */
    public function setActiveDebugger($param);

    /**
     * @return bool
     */
    public function isActiveDebugger();

    /**
     * @param $param
     * @return mixed
     */
    public function setActiveHealth($param);

    /**
     * @return bool
     */
    public function isActiveHealth();

    /**
     * @param $param
     * @return mixed
     */
    public function setActiveUsage($param);

    /**
     * @return bool
     */
    public function isActiveUsage();

    /**
     * @param string $param
     * @return $this
     */
    public function setDebuggerUrl($param);

    /**
     * @return string
     */
    public function getDebuggerUrl();

    /**
     * @param string $param
     * @return $this
     */
    public function setHealthUrl($param);

    /**
     * @return string
     */
    public function getHealthUrl();

    /**
     * @param string $param
     * @return $this
     */
    public function setUsageUrl($param);

    /**
     * @return string
     */
    public function getUsageUrl();
}