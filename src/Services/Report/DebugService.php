<?php


namespace SALESmanago\Services\Report;

use SALESmanago\Model\Report\DebugModel;

/**
 * Request data log on chosen stages;
 *
 * Class DebugService
 * @package SALESmanago\Services\Health
 */
class DebugService extends AbstractReportService
{
    protected function setModel()
    {
        return new DebugModel();
    }

    protected function setUpRequest()
    {
        $this->RequestHelper->setUrl($this->conf->getDebuggerUrl());
    }

    public function setData($data)
    {
        // TODO: Implement setData() method.
        return $this;
    }
}