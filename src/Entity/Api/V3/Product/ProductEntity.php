<?php

namespace SALESmanago\Entity\Api\V3\Product;

use SALESmanago\Entity\AbstractEntity;

class ProductEntity extends AbstractEntity implements ProductEntityInterface
{
    protected $productId;
    protected $name;
    protected $mainCategory;
    protected $categoryExternalId;
    protected $categories = [];
    protected $description;
    protected $productUrl;
    protected $mainImageUrl;
    protected $imageUrls = [];
    protected $available;
    protected $active;
    protected $quantity;
    protected $price;
    protected $discountPrice;
    protected $unitPrice;

    /**
     * @var SystemDetailsInterface
     */
    protected $systemDetails;
    protected $customDetails;

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMainCategory()
    {
        return $this->mainCategory;
    }

    /**
     * @param mixed $mainCategory
     */
    public function setMainCategory($mainCategory)
    {
        $this->mainCategory = $mainCategory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryExternalId()
    {
        return $this->categoryExternalId;
    }

    /**
     * @param mixed $categoryExternalId
     */
    public function setCategoryExternalId($categoryExternalId)
    {
        $this->categoryExternalId = $categoryExternalId;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     * @return ProductEntity
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductUrl()
    {
        return $this->productUrl;
    }

    /**
     * @param mixed $productUrl
     */
    public function setProductUrl($productUrl)
    {
        $this->productUrl = $productUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMainImageUrl()
    {
        return $this->mainImageUrl;
    }

    /**
     * @param mixed $mainImageUrl
     */
    public function setMainImageUrl($mainImageUrl)
    {
        $this->mainImageUrl = $mainImageUrl;
        return $this;
    }

    /**
     * @return array
     */
    public function getImageUrls()
    {
        return $this->imageUrls;
    }

    /**
     * @param array $imageUrls
     * @return ProductEntity
     */
    public function setImageUrls(array $imageUrls)
    {
        $this->imageUrls = $imageUrls;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * @param mixed $discountPrice
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSystemDetails()
    {
        return $this->systemDetails;
    }

    /**
     * @param mixed $systemDetails
     */
    public function setSystemDetails(SystemDetailsInterface $systemDetails)
    {
        $this->systemDetails = $systemDetails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomDetails()
    {
        return $this->customDetails;
    }

    /**
     * @param mixed $customDetails
     */
    public function setCustomDetails($customDetails)
    {
        $this->customDetails = $customDetails;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
              "productId"          => $this->productId,
              "name"               => $this->name,
              "mainCategory"       => $this->mainCategory,
              "categoryExternalId" => $this->categoryExternalId,
              "categories"         => $this->categories,
              "description"        => $this->description,
              "productUrl"         => $this->productUrl,
              "mainImageUrl"       => $this->mainImageUrl,
              "imageUrls"          => $this->imageUrls,
              "available"          => $this->available,
              "active"             => $this->active,
              "quantity"           => $this->quantity,
              "price"              => $this->price,
              "discountPrice"      => $this->discountPrice,
              "unitPrice"          => $this->unitPrice,
              "systemDetails"      => $this->systemDetails,
              "customDetails" => [//todo
                "detail1" => "linen",
                "detail2" => "short"
              ]
        ];
    }
}