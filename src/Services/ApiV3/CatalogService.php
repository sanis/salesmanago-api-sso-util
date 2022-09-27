<?php

namespace SALESmanago\Services\ApiV3;

use SALESmanago\Entity\ApiV3ConfigurationInterface;
use SALESmanago\Services\ApiV3\RequestService;

class CatalogService implements ApiV3Service
{
    const
        REQUEST_METHOD_POST = 'POST',
        REQUEST_METHOD_GET  = 'GET',
        API_METHOD          = '/v3/product/catalogList';

    /**
     * @var ApiV3ConfigurationInterface
     */
    private $configutation;

    /**
     * @var RequestService
     */
    private $RequestService;

    /**
     * @param ApiV3ConfigurationInterface $configuration
     * @throws \SALESmanago\Exception\Exception
     */
    public function __construct(ApiV3ConfigurationInterface $configuration)
    {
        $this->configutation = $configuration;
        $this->RequestService = new RequestService($configuration);
    }

    public function getCatalogs()
    {
        $Response = $this->RequestService->request(
            self::REQUEST_METHOD_GET,
            self::API_METHOD,
            null
        );

        return $Response;

        return $this->RequestService->validateCustomResponse(
            $Response,
            array(
                function () use ($Response, $Coupon)  {
                    return $Response->getField('coupon') == $Coupon->getCoupon();
                }
            )
        );
    }
}