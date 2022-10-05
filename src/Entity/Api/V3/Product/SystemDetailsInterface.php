<?php

namespace SALESmanago\Entity\Api\V3\Product;

use JsonSerializable;

interface SystemDetailsInterface extends JsonSerializable
{
    /**
     * @return SystemDetailsInterface
     */
    public function getBrand();

    /**
     * @param mixed $brand
     * @return SystemDetailsInterface
     */
    public function setBrand($brand);

    /**
     * @return mixed
     */
    public function getManufacturer();

    /**
     * @param mixed $manufacturer
     * @return SystemDetailsInterface
     */
    public function setManufacturer($manufacturer);

    /**
     * @return mixed
     */
    public function getPopularity();

    /**
     * @param mixed $popularity
     * @return SystemDetailsInterface
     */
    public function setPopularity($popularity);

    /**
     * @return mixed
     */
    public function getGender();

    /**
     * @param mixed $gender
     * @return SystemDetailsInterface
     */
    public function setGender($gender);

    /**
     * @return mixed
     */
    public function getSeason();

    /**
     * @param mixed $season
     */
    public function setSeason($season);

    /**
     * @return mixed
     */
    public function getColor();

    /**
     * @param mixed $color
     * @return SystemDetailsInterface
     */
    public function setColor($color);

    /**
     * @return bool
     */
    public function isBestseller();

    /**
     * @param bool $bestseller
     * @return SystemDetailsInterface
     */
    public function setBestseller(bool $bestseller);

    /**
     * @return bool
     */
    public function isNewProduct();

    /**
     * @param bool $newProduct
     * @return SystemDetailsInterface
     */
    public function setNewProduct(bool $newProduct);
}