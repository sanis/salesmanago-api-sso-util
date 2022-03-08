<?php


namespace SALESmanago\Services\Report;


use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Exception\Exception;
use SALESmanago\Model\Report\ReportModel;
use SALESmanago\Entity\Reporting\Platform;
use SALESmanago\Services\ContactAndEventTransferService;

/**
 * Class ReportService
 * Please instantiating this as soon as get proper ReportConfigurationInterface configuration
 *
 * @package SALESmanago\Services\Report
 */
class ReportService
{
    /**
     * @var ConfigurationInterface
     */
    private $conf;

    /**
     * @var ReportModel
     */
    private $reportModel;

    /**
     * @var ContactAndEventTransferService
     */
    private $transferService;

    /**
     * @var array of instances
     */
    private static $instances = [];

    /**
     * ReportService constructor.
     *
     * @param ConfigurationInterface $conf
     * @param Platform $platform
     */
    private function __construct(ConfigurationInterface $conf, Platform $platform)
    {
        $this->conf = $conf;
        $this->conf->setEndpoint('https://survey.salesmanago.com/');

        $this->reportModel = new ReportModel($this->conf, $platform);
        $this->transferService = new ContactAndEventTransferService($this->conf);
    }

    protected function __clone() {}

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * @param ConfigurationInterface|null $conf
     * @param Platform|null $platform
     * @return mixed|static
     * @throws \Exception
     */
    public static function getInstance($conf = null, $platform = null)
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {

            if ($conf === null || $platform === null) {
                throw new \Exception("Cannot instantiate an ReportService.");
            }

            self::$instances[$cls] = new static($conf, $platform);
        }

        return self::$instances[$cls];
    }

    /**
     * @param $actType - one of ReportModel const ACT_...
     * @param array $additionalInformation
     *
     * @return false
     */
    public function reportAction($actType, $additionalInformation = [])
    {
        if (!$this->conf->getActiveReporting()) {
            return false;
        }

        try {
            $this->transferService->transferBoth(
                $this->reportModel->getClientAsContact($actType),
                $this->reportModel->getActionAsEvent($actType, $additionalInformation)
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Report exceptions from SALESmanago\Exception\Exception
     *
     * @param $exceptionViewMessage
     * @return false
     */
    public function reportException($exceptionViewMessage)
    {
        return $this->reportAction(ReportModel::ACT_EXCEPTION, [$exceptionViewMessage]);
    }
}
