<?php


namespace SALESmanago\Model\Report;

use \DateTime;
use SALESmanago\Entity\Configuration as Conf;


abstract class AbstractBasicReportModel implements ReportModelInterface
{
    const
        TYPE = 'type',
        BASIC_DATA = 'basicData',
        DATA = 'data';

    /**
     * @var mixed
     */
    protected $rawData;

    /**
     * @return mixed
     */
    public abstract function getDataToSend();

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->rawData = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getBasicReportData()
    {
        try {
            $date = new DateTime();

            return [
                'createdOn' => $date->getTimestamp(),
                'timeZone' => $date->getTimezone()->getName(),
                'clientId' => Conf::getInstance()->getClientId(),
                'AppEndpoint' => Conf::getInstance()->getEndpoint(),
                'PHP' => phpversion(),
                'httpHost' => $_SERVER['HTTP_HOST'],
                'serverName' => $_SERVER['SERVER_NAME'],
                'serverRequestUri' => $_SERVER['REQUEST_URI']
            ];
        } catch (\Exception $e) {
            return ['reportServiceError' => 'while generates basic report data'];
        }

    }
}