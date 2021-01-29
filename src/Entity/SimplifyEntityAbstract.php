<?php


namespace SALESmanago\Entity;


use SALESmanago\Exception\Exception;

class SimplifyEntityAbstract
{
    /**
     * @var array - methods and fields available for Entity;
     */
    protected $methods = [];

    /**
     * @var array - of pre defined checkers functions, default value;
     */
    protected $methodsOptions = [
        'checkers' => [],
        'defaultValue' => null,
    ];

    public function __call($method, $args)
    {
        if (!in_array(substr($method, 0, 3), ['get', 'set'])
        && !method_exists($this, $method)) {
            throw new Exception('Method does\'nt exist' );
        }

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }

        $apiField = lcfirst(substr($method, 4, strlen($method)));

        if (!array_key_exists($apiField, $this->methods)) {
            throw new Exception('Method or field does\'nt exist' );
        }

        if('get' == substr($method, 0, 3)) {
            return $this->get($apiField);
        }

        if('set' == substr($method, 0, 3)) {
            return $this->set($apiField, $args);
        }
    }

    /**
     * @param $apiField
     * @param $args
     * @return $this
     */
    protected function set($apiField, $args)
    {
        if (isset($this->methods[$apiField]['checkers'])) {
            if(is_array($this->methods[$apiField]['checkers'])) {
                foreach ($this->methods[$apiField]['checkers'] as $checker) {
                    if (!$this->$checker($args)) {
                        throw new Exception("$checker failed");
                    }
                }
            } else if (!empty($this->methods[$apiField]['checkers'])) {
                if (!$this->$this->methods[$apiField]['checkers']($args)) {
                    throw new Exception("$this->methods[$apiField]['checkers'] failed");
                }
            }
        }


    }

    /**
     * @param string $apiField
     */
    protected function get($apiField) {
        return;
    }
}