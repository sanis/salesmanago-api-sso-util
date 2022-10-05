<?php

namespace SALESmanago\Entity\Api\V3;

use JsonSerializable;

interface CatalogEntityInterface extends JsonSerializable
{
    const CATALOG_ID = 'catalogId';

    /**
     * @return string
     */
    public function getCatalogId();

    /**
     * @param string $catalogId
     * @return CatalogEntityInterface
     */
    public function setCatalogId($catalogId);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param string $name
     * @return CatalogEntityInterface
     */
    public function setName($name);

    /**
     * @return bool
     */
    public function getSetAsDefault();

    /**
     * @param bool $setAsDefault
     * @return CatalogEntityInterface
     */
    public function setSetAsDefault($setAsDefault);

    /**
     * @return mixed
     */
    public function getCurrency();

    /**
     * @param string $currency
     * @return CatalogEntityInterface
     */
    public function setCurrency($currency);

    /**
     * @return string
     */
    public function getLocation();

    /**
     * @param string $location
     * @return CatalogEntityInterface
     */
    public function setLocation($location);
}