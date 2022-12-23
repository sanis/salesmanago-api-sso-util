<?php

namespace SALESmanago\Entity\Api\V3;

use SALESmanago\Entity\AbstractEntity;
use SALESmanago\Exception\Exception;
use SALESmanago\Helper\DataHelper;

class CatalogEntity extends AbstractEntity implements CatalogEntityInterface
{
    /**
     * @var string
     */
    private $catalogId;

    /**
     * @var string
     */
    private $catalogName;

    /**
     * @var string the same as catalog name TODO must be merge with $catalogName
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
     * @param array|null $data - key => value array, where key is a CatalogEntity attribute;
     * @throws Exception
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->setDataWithSetters($data);
        }
    }

    /**
     * @return string
     */
    public function getCatalogId()
    {
        return $this->catalogId;
    }

    /**
     * @param string $catalogId
     * @return CatalogEntity
     */
    public function setCatalogId($catalogId)
    {
        $this->catalogId = $catalogId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCatalogName()
    {
        return $this->catalogName;
    }

    /**
     * @param string $name
     * @return CatalogEntity
     */
    public function setCatalogName($name)
    {
        $this->catalogName = $name;
        return $this;
    }

    /**
     * TODO must be merge with $catalogName
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * TODO must be merge with $catalogName
     * @param string $name
     * @return CatalogEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSetAsDefault()
    {
        return $this->setAsDefault;
    }

    /**
     * @param bool $setAsDefault
     * @return CatalogEntity
     */
    public function setSetAsDefault($setAsDefault)
    {
        $this->setAsDefault = $setAsDefault;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return CatalogEntity
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return CatalogEntity
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return DataHelper::filterDataArray([
            "catalogId"    => $this->catalogId,
            "catalogName"  => ($this->catalogName === null) ? $this->name : $this->catalogName,
            "setAsDefault" => $this->setAsDefault,
            "currency"     => $this->currency,
            "location"     => $this->location
        ]);
    }
}
