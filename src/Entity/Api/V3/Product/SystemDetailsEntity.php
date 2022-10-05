<?php

namespace SALESmanago\Entity\Api\V3\Product;

class SystemDetailsEntity implements SystemDetailsInterface
{
    protected $brand;
    protected $manufacturer;
    protected $popularity;
    protected $gender;
    protected $season;
    protected $color;

    /**
     * @var bool
     */
    protected $bestseller;

    /**
     * @var bool
     */
    protected $newProduct;

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     * @return SystemDetailsEntity
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturer
     * @return SystemDetailsEntity
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @param mixed $popularity
     * @return SystemDetailsEntity
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return SystemDetailsEntity
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     * @return SystemDetailsEntity
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBestseller(): bool
    {
        return $this->bestseller;
    }

    /**
     * @param bool $bestseller
     * @return SystemDetailsEntity
     */
    public function setBestseller(bool $bestseller)
    {
        $this->bestseller = $bestseller;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewProduct(): bool
    {
        return $this->newProduct;
    }

    /**
     * @param bool $newProduct
     * @return SystemDetailsEntity
     */
    public function setNewProduct(bool $newProduct)
    {
        $this->newProduct = $newProduct;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "brand"        => $this->brand,
            "manufacturer" => $this->manufacturer,
            "popularity"   => $this->popularity,
            "gender"       => $this->gender,
            "season"       => $this->season,
            "color"        => $this->color,
            "bestseller"   => $this->bestseller,
            "newProduct"   => $this->newProduct
        ];
    }
}