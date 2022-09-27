<?php

namespace SALESmanago\Services\ApiV3;

use SALESmanago\Entity\ApiV3ConfigurationInterface;

interface ApiV3Service
{
    public function __construct(ApiV3ConfigurationInterface $configuration);
}