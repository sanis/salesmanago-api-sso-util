<?php


namespace SALESmanago\Entity\Feed\Ceneo\Product;

use SALESmanago\Exception\Exception;
use SALESmanago\Entity\Feed\FeedItem;
use SALESmanago\Entity\AbstractEntity;

class Product extends AbstractEntity implements FeedItem
{

    /**
     * @var string[]
     */
    private static $fields = [
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

    /**
     * @var array
     */
    private static $fieldsDefVal = [
        'id' => null,
        'url' => null,
        'price' => null,
        'avail' => null,
        'set' => null,
        'weight' => null,
        'basket' => null,
        'stock' => null,
        'cat' => null,
        'name' => null,
        'desc' => null,
        'mainImgUrl' => null,
        'imgsAdditionalUrl' => [],
        'attrs' => [],
        'customAttributes' => [],
        'customTags' => []
    ];

    /**
     * This is necessary for platforms like Magento where new object is creating by DI.
     * @return static - return new instace of current class.
     */
    public static function getNewInstance()
    {
        return new static();
    }

    protected function resetFields()
    {
        $fields = self::$fields;
        $fieldsDefVal = self::$fieldsDefVal;
//@todo
//        array_walk(self::$fields, function ($item) use ($fields, $fieldsDefVal)  {
//            'set'.ucfirst($item);
//        });
    }

    /**
     * @param $method
     * @param $args
     * @return false|mixed
     * @throws Exception
     */
    public function __call($method, $args) {

        $action = substr($method, 0, 3);

        if (!in_array($action, ['get', 'set'])) {
            throw new Exception(
                sprintf('Invalid method %s::%s(%s)', get_class($this), $method, print_r($args, 1))
            );
        }

        $field = lcfirst(str_replace($action, '', $method));

        if (!in_array($field, self::$fields)) {
            throw new Exception(
                sprintf('Invalid field %s::%s(%s)', get_class($this), $field, print_r($args, 1))
            );
        }

        switch ($action) {
            case 'set':
                $this->$field = $args;
                return $this;
            case 'get':
                return $this->$field;
        }
    }

    private function __set($field, $value)
    {
        $this->$field = $value;
    }

    private function __get($field)
    {
        if (!in_array($field, self::$fields)) {
            throw new Exception(
                sprintf('Undefined field %s::%s', get_class($this), $field)
            );
        }

        if (!isset($this->$field)) {
            return self::$fieldsDefVal[$field];
        }

        return $this->$field;
    }

    /**
     * @param $name
     * @param $url
     * @return $this
     */
    public function setImgAdditionalUrl($url)
    {
        $this->imgsAdditionalUrl[] = $url;
        return $this;
    }

    /**
     * @param $name
     * @param $val
     * @return $this
     */
    public function setAttr($name, $val)
    {
        $this->attrs[$name] = $val;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function unsAttr($name)
    {
        unset($this->attrs[$name]);
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setCustomAttribute($name, $value)
    {
        $this->customAttributes[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function unsCustomAttribute($name)
    {
        unset($this->customAttributes[$name]);
        return $this;
    }

    /**
     * @param $name
     * @param $val
     * @return $this
     */
    public function setCustomTags($name, $val)
    {
        $this->customTags[$name] = $val;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function unsCustomTags($name)
    {
        unset($this->customTags[$name]);
        return $this;
    }















//    /**
//     * please keep all const values in camelCase - with small sight start;
//     */
//    const P_ID     = 'id';
//    const P_SKU    = 'sku';
//    const P_URL    = 'url';
//    const P_PRICE  = 'price';
//    const P_AVAIL  = 'avail';
//    const P_SET    = 'set';
//    const P_WEIGHT = 'weight';
//    const P_BASKET = 'basket';
//    const P_STOCK  = 'stock';
//
//    protected $id;
//    protected $url;
//    protected $price;
//    protected $avail;
//    protected $set;
//    protected $weight;
//    protected $basket;
//    protected $stock;
//    protected $cat;
//    protected $name;
//    protected $desc;
//    protected $mainImgUrl;
//    protected $imgsAdditionalUrl;
//    protected $attrs;
//    protected $customAttributes;
//    protected $customTags;
//
//    public function __construct()
//    {
//        self::resetProduct();
//    }
//
//    /**
//     * @param string|int $id
//     * @return $this
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//        return $this;
//    }
//
//    /**
//     * @param string|int $sku
//     * @return $this
//     */
//    public function setSku($sku)
//    {
//        $this->id = $sku;
//        return $this;
//    }
//
//    /**
//     * @param string $url
//     * @return $this
//     */
//    public function setUrl($url)
//    {
//        $this->url = $url;
//        return $this;
//    }
//
//    /**
//     * @param string|int $price
//     * @return $this
//     */
//    public function setPrice($price)
//    {
//        $this->price = $price;
//        return $this;
//    }
//
//    /**
//     * @param string|int $avail
//     * @return $this
//     * dostępność produktu. Dostępne opcje [1, 3, 7, 14, 90, 99, 110] gdzie: 1 – dostępny,
//     * sklep wyśle produkt w ciągu 24 godzin, 3 – sklep wyśle produkt do 3 dni, 7 – sklep wyśle
//     * produkt w ciągu tygodnia, 14 – sklep wyśle produkt do 14 dni, 90 – towar na zamówienie, 99
//     * – brak informacji o dostępności – status „sprawdź w sklepie”, 110 – przedsprzedaż
//     **/
//    public function setAvail($avail)
//    {
//        $this->avail = $avail;
//        return $this;
//    }
//
//    /**
//     * @param string|int $set
//     * @return $this;
//     * zestaw. Czy oferta jest zestawem. Dostępne opcje [1, 0] gdzie; 1 – tak, oferta jest
//     * zestawem, 0 – nie, oferta nie jest zestawem. Opcjonalnie
//     */
//    public function setSet($set)
//    {
//        $this->set = $set;
//        return $this;
//    }
//
//    /**
//     * @param string|int $weight
//     * @return $this;
//     */
//    public function setWeight($weight)
//    {
//        $this->weight = $weight;
//        return $this;
//    }
//
//    /**
//     * dotyczy sklepów aktywnych w usłudze „Kup na Ceneo”. Czy oferta ma być dostępna
//     * w Kup na Ceneo. Dostępne opcje [1, 0] gdzie; 1 – tak, oferta dostępna w Kup na Ceneo, 0 –
//     * nie, oferta niedostępna w Kup na Ceneo. Opcjonalnie
//     */
//    public function setBasket($basket)
//    {
//        $this->basket = self::P_BASKET.'="'.$basket.'" ';
//        return $this;
//    }
//
//    /**
//     * stan magazynowy. Liczna całkowita dodatnia. Pole nie może być puste. Opcjonalnie
//     **/
//    public function setStock($stock)
//    {
//        $this->stock = self::P_STOCK.'="'.$stock.'" ';
//        return $this;
//    }
//
//    public function setCat($cat)
//    {
//        $this->cat = '<cat><![CDATA['.$cat.']]></cat>';
//        return $this;
//    }
//
//    public function setName($name)
//    {
//        $this->name ='<name><![CDATA['.$name.']]></name>';
//        return $this;
//    }
//
//    public function setImgMain($mainImgUrl)
//    {
//        $this->mainImgUrl = $mainImgUrl;
//        return $this;
//    }
//
//    public function setImgsAdditional($imgsAdditionalUrl)
//    {
//        $this->imgsAdditionalUrl[] = $imgsAdditionalUrl;
//        return $this;
//    }
//
//    public function setDesc($desc)
//    {
//        $this->desc = '<desc><![CDATA['.$desc.']]></desc>';
//        return $this;
//    }
//
//    public function setAttr($name, $value)
//    {
//        $attr = '<a name="'.$name.'"><![CDATA['.$value.']]></a>';
//
//        $this->attrs[] = $attr;
//
//        return $this;
//    }
//
//    public function setAttrs($array)
//    {
//        foreach ($array as $attrName => $attrValue) {
//            $this->setAttr($attrName, $attrValue);
//        }
//
//        return $this;
//    }
//
//    /**
//     * @name - string - name of attribute which be added to <o> tag;
//     * @value - string - value of new attribute;
//     */
//    public function setCustomAttribute($name, $value)
//    {
//        $this->customAttributes[] = $name.'="'.$value.'" ';
//        return $this;
//    }
//
//    public function setCustomAttributes($array)
//    {
//        foreach ($array as $attrName => $attrValue) {
//            $this->setCustomAttribute($attrName, $attrValue);
//        }
//
//        return $this;
//    }
//
//    public function setCustomTag($name, $value)
//    {
//        $this->customTags[] = '<'.$name.'><![CDATA['.$value.']]></'.$name.'>';
//        return $this;
//    }
//
//    public function setCustomTags($array)
//    {
//        foreach ($array as $tagName => $tagValue) {
//            $this->setCustomTag($tagName, $tagValue);
//        }
//
//        return $this;
//    }
//
//    protected function buildCustomTags()
//    {
//        if (!empty($this->customTags)) {
//            $customAttrs = implode('', $this->customTags);
//        }
//
//        return (!empty($customAttrs)) ? $customAttrs : '';
//    }
//
//    protected function buildCustomAttributes()
//    {
//        if (!empty($this->customAttributes)) {
//            $customAttrs = implode('', $this->customAttributes);
//        }
//
//        return (!empty($customAttrs)) ? $customAttrs : '';
//    }
//
//    protected function buildAttrs()
//    {
//        if (!empty($this->attrs)) {
//            $attrs = '<attrs>'.implode('', $this->attrs).'</attrs>';
//        }
//
//        return (!empty($attrs)) ? $attrs : '';
//    }
//
//    protected function buildImages()
//    {
//        if (!empty($this->mainImgUrl)) {
//            $main = '<main url="'.$this->mainImgUrl.'" />';
//        }
//
//        if (!empty($this->imgsAdditionalUrl)) {
//            foreach ($this->imgsAdditionalUrl as $additionalUrl) {
//                $additional[] = '<i url="'.$additionalUrl.'" />';
//            }
//        }
//
//        $buildImages = isset($main)
//            ? $main
//            : '';
//        $buildImages = isset($additional)
//            ? $buildImages.implode('', $additional)
//            : '';
//
//        return $buildImages;
//    }
//
//    protected function resetProduct()
//    {
//        $this->id                = '';
//        $this->url               = '';
//        $this->price             = '';
//        $this->avail             = '';
//        $this->set               = '';
//        $this->weight            = '';
//        $this->basket            = '';
//        $this->stock             = '';
//        $this->cat               = '';
//        $this->name              = '';
//        $this->desc              = '';
//        $this->mainImgUrl        = '';
//        $this->imgsAdditionalUrl = [];
//        $this->attrs             = [];
//        $this->customAttributes  = [];
//        $this->customTags        = [];
//    }
}