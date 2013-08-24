<?php
namespace Draeli\RssBridge;

interface CacheInterface{
    /**
    * Try to load data
    * @return mixed data|false if there's not data to load
    */
    public function loadData();

    /**
    * @param mixed $datas Datas to store
    * @return this
    */
    public function saveData($datas);

    /**
    * Must return time for last modification or false if there's not cache
    * @return (timestamp Unix)|false
    */
    public function getTime();
}