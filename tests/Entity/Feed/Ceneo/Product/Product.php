<?php


namespace Tests\Entity\Feed;

use Faker;
use PHPUnit\Framework\TestCase;
use SALESmanago\Entity\Feed\Ceneo\Product\Product as CeneoProduct;

class Product extends TestCase
{
    public static $Product;

    public static $fields = [
        'id',
        'url',
        'price',
        'avail',
        'set',
        'weight',
        'basket',
        'stock',
        'cat',
        'name',
        'desc',
        'mainImgUrl',
        'imgsAdditionalUrl',
        'attrs',
        'customAttributes',
        'customTags'
    ];

    public function setUp() {
        self::$Product = new CeneoProduct();
    }

    public function testGetNewInstanse()
    {
        $instances = [];
        $instances[0] = self::$Product::getNewInstance();
        $instances[1] = self::$Product::getNewInstance();

        $this->assertFalse($instances[0] === $instances[1]);
    }

//    /**
//     * check if all class field is settable
//     */
//    public function testCheckSetAvailableFields()
//    {
//        foreach (self::$fields as $field) {
//            $methodName = 'set'.ucfirst($field);
//            self::$Product->$methodName($this->getFakerValueForField()[$field]);
//        }
//    }
//
//    protected function getFakerValueForField()
//    {
//        //@todo finish this one:
//        $faker = Faker\Factory::create();
//
//        return [
//            'id'     => $faker->uuid,
//            'url'    => $faker->url,
//            'price'  => $faker->randomNumber(),
//            'avail'  => $faker->randomDigit,
//            'set'    => $faker->boolean,
//            'weight' => $faker->randomNumber(),
//            'basket' => $faker->boolean,
//            'stock'  => $faker->randomDigit,
//            'cat'    => $faker->word,
//            'name'   => $faker->word,
//            'desc'   => $faker->text(200),
//            'mainImgUrl'        => $faker->url,
//            'imgsAdditionalUrl' => $faker->url,
//            'attrs'  => $faker->word,
//            'customAttributes'  => $faker->word,
//            'customTags'        => $faker->word
//        ];
//    }
}