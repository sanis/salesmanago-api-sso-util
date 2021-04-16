<?php


namespace SALESmanago\Services\Report;

use SALESmanago\Model\Report\HealthModel;

/**
 * Exception log to external SM server
 *
 * Class HealthService
 * @package SALESmanago\Services\Health
 */
class HealthService extends AbstractReportService
{
    protected function setModel()
    {
        return new HealthModel();
    }

    protected function setUpRequest()
    {
        $this->RequestHelper->setUrl($this->conf->getHealthUrl());
    }

    public function setData($data)
    {
        $this->model->setData($data);
        return $this;
    }
}