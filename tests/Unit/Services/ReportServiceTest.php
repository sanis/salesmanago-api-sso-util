<?php


namespace Tests\Unit\Services;


use SALESmanago\Entity\Reporting\Platform;
use Tests\Unit\TestCaseUnit;
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
     * @throws \Exception
     */
    public function testReportActionSuccess()
    {
        $faker    = Faker\Factory::create();
        $conf     = $this->createConfigurationForReportService();
        $platform = $this->createPlatform();

        ReportService::getInstance($conf, $platform);

        $response = ReportService::getInstance()->reportAction(ReportModel::ACT_TEST, [$faker->text(2000)]);
        $this->assertTrue($response);
    }

    /**
     * @return ConfigurationInterface
     */
    protected function createConfigurationForReportService()
    {
        $faker = Faker\Factory::create();

        return Configuration::getInstance()
            ->setOwner($faker->email)
            ->setClientId($faker->uuid)
            ->setActiveReporting(true);
    }

    /**
     * @return Platform
     */
    protected function createPlatform()
    {
        $faker    = Faker\Factory::create();
        $platform = new Platform();

        $platform
            ->setName($faker->word)
            ->setVersion($this->generateVersion())
            ->setVersionOfIntegration($this->generateVersion())
            ->setLang($faker->languageCode)
            ->setPlatformDomain($faker->url);

        return $platform;
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