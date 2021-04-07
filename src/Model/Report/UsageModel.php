<?php


namespace SALESmanago\Model\Report;


class UsageModel extends AbstractBasicReportModel
{
    public function getDataToSend()
    {
        return array_merge($this->getBasicReportData(), ['usage', $this->rawData]);
    }
}