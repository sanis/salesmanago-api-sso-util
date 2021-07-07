<?php


namespace SALESmanago\Services\Report;


use SALESmanago\Model\Report\UsageModel;

/**
 * Platform/client/usage basic data report to SM
 * Class ReportService
 * @package SALESmanago\Services\Health
 */
class UsageService extends AbstractReportService
{
    protected function setModel()
    {
        return new UsageModel();
    }

    protected function setUpRequest()
    {
        $this->RequestHelper->setUrl($this->conf->getUsageUrl());
    }

    public function setData($data)
    {
        $this->model->setData($data);
        return $this;
    }
}