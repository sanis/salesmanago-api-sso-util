<?php


namespace SALESmanago\Model\Report;

class HealthModel extends AbstractBasicReportModel
{

    public function getDataToSend()
    {
        return array_merge($this->getBasicReportData(), ['error' => $this->rawData]);
    }
}