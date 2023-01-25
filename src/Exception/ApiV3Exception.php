<?php

namespace SALESmanago\Exception;

class ApiV3Exception extends Exception
{
    /**
     * @var array
     */
    private $codes = [];

    /**
     * @var array
     */
    private $messages = [];

    /**
     * Sets error codes
     *
     * @param array $codes
     * @return $this
     */
    public function setCodes(array $codes)
    {
        $this->codes = $codes;
        return $this;
    }

    /**
     * Return error codes
     *
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * Set messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Return messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Combined error codes with messages [errorCode => errorMessage]
     *
     * @return array
     */
    public function getCombined()
    {
        $combined = [];
        foreach ($this->getCodes() as $index => $code) {
            $combined[$code] = $this->messages[$index];
        }
        return $combined;
    }
}