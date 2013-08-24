<?php
namespace Draeli\RssBridge;

/**
* Object to store datas collect informations
* FIXME : not sur this logic is the good, I think recast all is necessary
*/
class Item{
    public function __set($name, $value){
        $this->$name = $value;
    }

    public function __get($name){
        return isset($this->$name) ? $this->$name : null;
    }
}