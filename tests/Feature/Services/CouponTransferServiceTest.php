<?php

namespace Tests\Feature\Services;

use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\cUrlClientConfiguration;
use Tests\Feature\TestCaseUnit;
use SALESmanago\Services\CouponTransferService;
use SALESmanago\Entity\Contact\Coupon;
use SALESmanago\Entity\Contact\Contact;
use Faker;
use SALESmanago\Exception\Exception;
use SALESmanago\Services\ContactAndEventTransferService;
use SALESmanago\Entity\Response;

class CouponTransferServiceTest extends TestCaseUnit
{
    /**
     * Test transfer coupon success
     *
     * @return void
     * @throws Exception
     */
    public function testTransferSuccess()
    {
        $Conf = $this->initConf();

        //for coupon SALESmanago api method timeouts must be longer:
        $Conf->setRequestClientConf(
            $Conf->getRequestClientConf([
                cUrlClientConfiguration::HOST               => $Conf->getEndpoint(),
                cUrlClientConfiguration::TIMEOUT_MS         => 10000,
                cUrlClientConfiguration::CONNECT_TIMEOUT_MS => 10000
            ])
        );

        $ContactCreatedInApp = self::createNotAsyncContactInSalesmanagoApp($Conf);
        $Coupon = self::createRandomCouponEntity();

        $Response = self::createCouponForContactInSalesmanagoApp($Conf, $ContactCreatedInApp, $Coupon);

        $this->assertTrue($Response->isSuccess());
        $this->assertEquals($Response->getField('coupon'), $Coupon->getCoupon());
    }

    /**
     * @param ConfigurationInterface $Config
     * @param Contact $Contact
     * @param Coupon $Coupon
     * @return Response
     * @throws Exception
     */
    public static function createCouponForContactInSalesmanagoApp(
        ConfigurationInterface $Config,
        Contact $Contact,
        Coupon $Coupon
    ) {
        $CouponTransferService = new CouponTransferService($Config);
        return $CouponTransferService->transfer($Contact, $Coupon);
    }

    /**
     * @param ConfigurationInterface $Config
     * @return bool|Contact
     * @throws Exception
     */
    public static function createNotAsyncContactInSalesmanagoApp(ConfigurationInterface $Config)
    {
        $Contact = self::createRandomContactEntity();
        $TransferService = new ContactAndEventTransferService($Config);
        $Response = $TransferService->transferContact($Contact->setOptions($Contact->getOptions()->setAsync(false)));

        if ($Response->isSuccess()) {
            return $Contact;
        }

        return $Response->isSuccess();
    }

    /**
     * @return Coupon
     * @throws Exception
     */
    public static function createRandomCouponEntity()
    {
        $faker = Faker\Factory::create();
        return new Coupon(
            [
                'name' => $faker->word,
                'length' => $faker->numberBetween(1, 12345),
                'valid' => (time() + (60 * 60 * 24 * 360))*1000,
                'coupon' => $faker->text(30),
            ]
        );
    }

    /**
     * @return Contact
     * @throws Exception
     */
    public static function createRandomContactEntity()
    {
        $faker = Faker\Factory::create();
        $Contact = new Contact();
        return $Contact->set([
            'name' => $faker->name,
            'email' => $faker->email,
        ]);

    }
}