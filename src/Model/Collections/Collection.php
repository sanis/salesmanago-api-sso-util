<?php


namespace SALESmanago\Model\Collections;


interface Collection
{
    public function clear();
    public function copy();
    public function isEmpty();
    public function toArray();
}