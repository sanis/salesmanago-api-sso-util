<?php

namespace Tests\Feature\Services\API\V3;

use SALESmanago\Entity\Api\V3\ConfigurationEntity;
use Tests\Feature\TestCaseUnit;

class AbstractBasicV3ServiceTest extends TestCaseUnit
{
    protected function createConfigurationEntity()
    {
        ConfigurationEntity::getInstance()
            ->setApiV3Endpoint(getenv('ApiV3Endpoint'))
            ->setApiV3Key(getenv('ApiV3Key'));
    }
}