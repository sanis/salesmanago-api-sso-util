<?php

namespace SALESmanago\Model;


interface UserInterface
{
    public function delete($userProperties);
    public function getUserToken($userProperties);
    public function refreshUserToken($userProperties);
    public function setCustomProperties($userProperties);
    public function getAccountUserData($userProperties);
    public function setAccountUserData($userProperties);
    public function getPlatformUserData($userProperties);
    public function setPlatformUserData($userProperties);
}