<?php

namespace SALESmanago\Entity\Contact;

use SALESmanago\Entity\AbstractEntity;
use SALESmanago\Exception\Exception;
use SALESmanago\Helper\EntityDataHelper;

class Properties extends AbstractEntity
{
    const
        PROPERTIES   = 'properties';

    private $properties = [];


    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->setDataWithSetters($data);
        }
    }

    /**
     * @param array $data;
     *
     * @return $this;
     * @throws Exception;
     */
    public function set($data) {
        $this->setDataWithSetters($data);
        return $this;
    }

    /**
     * @param array $param;
     *
     * @return $this;
     * @throws Exception;
     */
    public function setProperties($param)
    {
        if (!is_array($param)) {
            throw new Exception('Passed argument isn\'t array');
        }
        $this->properties = $param;
    }

    /**
     * @return string $this->properties;
     */
    public function getProperties(){
        return $this->properties;
    }
}
