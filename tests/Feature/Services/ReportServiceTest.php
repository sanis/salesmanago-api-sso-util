<?php


namespace Tests\Feature\Services;

use Exception;
use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\User;
use Tests\Feature\TestCaseUnit;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Services\Report\ReportService;
use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Report\ReportModel;
use Faker;

class ReportServiceTest extends TestCaseUnit
{
    /**
     * Test Report test Action
     *
     * @throws Exception
     */
    public function testReportActionSuccess()
    {
        $faker = Faker\Factory::create();
        $conf = $this->initConf();

        $conf->setActiveReporting(true)
            ->setPlatformName($faker->word)
            ->setPlatformVersion($this->generateVersion())
            ->setVersionOfIntegration($this->generateVersion())
            ->setPlatformDomain($faker->languageCode)
            ->setPlatformDomain($faker->url);

        $response = ReportService::getInstance($conf)
            ->reportAction(ReportModel::ACT_TEST, [$faker->text(2000)]);

        $this->assertTrue($response);
    }

    /**
     * @return string
     */
    protected function generateVersion()
    {
        $faker = Faker\Factory::create();
        return $faker->numberBetween(1, 10) . '.' . $faker->numberBetween(1, 10) . '.' . $faker->numberBetween(1, 10);
    }
}