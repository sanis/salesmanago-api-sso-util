<?php

namespace SALESmanago\Controller;

use SALESmanago\Model\IntegrationInterface;


class IntegrationController
{
    use ControllerTrait;

    protected $model;

    public function __construct(IntegrationInterface $model)
    {
        $this->model = $model;
    }

    public function logout($userProperties = array())
    {
        $this->model->delete($userProperties);
    }

    public function getUserConfig($userProperties = array())
    {
        $this->model->getUserConfig($userProperties);
    }

    public function getAccountUserData($userProperties = array())
    {
        return $this->model->getAccountUserData($userProperties);
    }

    public function setAccountUserData($userProperties = array())
    {
        return $this->model->setAccountUserData($userProperties);
    }

    public function getPlatformUserData($userProperties = array())
    {
        return $this->model->getPlatformUserData($userProperties);
    }

    public function setPlatformUserData($userProperties = array())
    {
        return $this->model->setPlatformUserData($userProperties);
    }

}