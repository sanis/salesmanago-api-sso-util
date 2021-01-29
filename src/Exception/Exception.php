<?php

namespace SALESmanago\Exception;


class Exception extends \Exception
{

    /**
     * @return string - massage for logs files
     */
    public function getLogMessage()
    {
        /**
         * @TODO change this one
         */
        return $this->getMessage();
    }

    /**
     * @return string - massage for view popups, tooltips, etc.
     */
    public function getViewMessage()
    {
        /**
         * @TODO change this one
         */
        return $this->getMessage();
    }

    /**
     * @return string - massage for browser console.
     */
    public function getConsoleMessage()
    {
        /**
         * @TODO change this one
         */
        return $this->getMessage();
    }
}
