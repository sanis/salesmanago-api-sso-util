<?php


namespace SALESmanago\Model\Report;


class DebugModel extends AbstractBasicReportModel
{
    /**
     * @return array
     */
    public function getDataToSend()
    {
        return [$this->getBasicReportData(), ['data' => $this->rawData]];
    }
}