<?php


namespace SALESmanago\Model\Report;

class HealthModel extends AbstractBasicReportModel
{
    /**
     * @return array|mixed
     */
    public function getDataToSend()
    {
        return array_merge($this->getBasicReportData(), ['error' => $this->rawData]);
    }
}