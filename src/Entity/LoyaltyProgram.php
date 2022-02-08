<?php

namespace SALESmanago\Entity;

use SALESmanago\Exception\Exception;
use SALESmanago\Model\LoyaltyProgramModel;

class LoyaltyProgram extends AbstractEntity
{
    protected $loyaltyProgramName = '';
    protected $modificationType = '';
    protected $addresseeType = '';
    protected $comment = '';
    protected $points = 0;
    protected $value;

    /**
     * LoyaltyProgramEntity constructor.
     *
     * @param array $data
     * @throws Exception
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->setDataWithSetters($data);
        }
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setLoyaltyProgramName($name)
    {
        $this->loyaltyProgramName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoyaltyProgramName()
    {
        return $this->loyaltyProgramName;
    }

    /**
     * Used only for /modifyPoints method.
     *
     * @param int $points
     * @return $this
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Used only for /modifyPoints method.
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Used only for /modifyPoints method.
     *
     * SUBTRACT - subtract points
     * ADD      - add points
     *
     * @param string $type
     * @return $this
     */
    public function setModificationType($type)
    {
        $this->modificationType = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getModificationType()
    {
        return $this->modificationType;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Example of types @see LoyaltyProgramModel
     *
     * @param string $type
     * @return $this
     */
    public function setAddresseType($type)
    {
        $this->addresseeType = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddresseType()
    {
        return $this->addresseeType;
    }
}
