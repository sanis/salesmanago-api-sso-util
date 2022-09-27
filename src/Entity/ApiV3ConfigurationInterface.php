<?php

namespace SALESmanago\Entity;

interface ApiV3ConfigurationInterface extends ConfigurationInterface
{
    /**
     * @return string
     */
    public function getApiKeyV3(): string;

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKeyV3(string $apiKey): self;

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setApiV3Endpoint(string $endpoint): self;

    /**
     * @return string
     */
    public function getApiV3Endpoint(): string;
}