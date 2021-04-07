<?php


namespace SALESmanago\Factories;


use SALESmanago\Entity\ReportConfigurationInterface;

use SALESmanago\Services\Report\DebugService;
use SALESmanago\Services\Report\HealthService;
use SALESmanago\Services\Report\UsageService;

class ReportFactory
{
    /**
     * @param ReportConfigurationInterface $conf
     * @param $data
     * @return bool
     */
    public static function doHealthReport(ReportConfigurationInterface $conf, $data)
    {
        if (!$conf->getActiveHealth()) {
            return true;
        }

        $healthReportService = new HealthService($conf);
        $healthReportService
            ->setData($data)
            ->doReport();
    }

    /**
     * @param ReportConfigurationInterface $conf
     * @param $data
     * @return bool
     */
    public static function doUsageReport(ReportConfigurationInterface $conf, $data)
    {
        if (!$conf->getActiveUsage()) {
            return true;
        }

        $healthReportService = new UsageService($conf);
        $healthReportService
            ->setData($data)
            ->doReport();
    }

    /**
     * @param ReportConfigurationInterface $conf
     * @param $data
     * @return bool
     */
    public static function doDebugReport(ReportConfigurationInterface $conf, $data)
    {
        if (!$conf->getActiveDebugger()) {
            return true;
        }

        $healthReportService = new DebugService($conf);
        $healthReportService
            ->setData($data)
            ->doReport();
    }
}