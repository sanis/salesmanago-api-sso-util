<?php

namespace SALESmanago\Entity;

class ProductCatalogEntity extends AbstractEntity
{
    /**
     * @var string
     */
    private $catalogId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $setAsDefault;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $location;

    /**
     * @return string
     */
    public function getCatalogId(): string
    {
        return $this->catalogId;
    }

    /**
     * @param string $catalogId
     * @return ProductCatalogEntity
     */
    public function setCatalogId(string $catalogId): ProductCatalogEntity
    {
        $this->catalogId = $catalogId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProductCatalogEntity
     */
    public function setName(string $name): ProductCatalogEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSetAsDefault(): bool
    {
        return $this->setAsDefault;
    }

    /**
     * @param bool $setAsDefault
     * @return ProductCatalogEntity
     */
    public function setSetAsDefault(bool $setAsDefault): ProductCatalogEntity
    {
        $this->setAsDefault = $setAsDefault;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return ProductCatalogEntity
     */
    public function setCurrency(string $currency): ProductCatalogEntity
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return ProductCatalogEntity
     */
    public function setLocation(string $location): ProductCatalogEntity
    {
        $this->location = $location;
        return $this;
    }
}