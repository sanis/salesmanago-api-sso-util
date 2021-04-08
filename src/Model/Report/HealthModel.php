<?php


namespace SALESmanago\Model\Report;

class HealthModel extends AbstractBasicReportModel
{
    const TYPE_HEALTH = 'health';

    /**
     * @return array|mixed
     */
    public function getDataToSend()
    {
        return [
            self::TYPE => self::TYPE_HEALTH,
            self::BASIC_DATA => $this->getBasicReportData(),
            self::DATA => $this->rawData
        ];
    }
}