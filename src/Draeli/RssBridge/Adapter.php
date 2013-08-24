<?php
namespace Draeli\RssBridge;

/**
* Allow to create a new adapter
* Note : actually are supported : Cache/Bridge/Format
*/
class Adapter{
    /**
    * To call a local adapter
    * @param string $typeAdapter Type of adapter (see class description for details)
    * @param string $nameSimple Depending on type and need to call without end prefix file name (Exemple : FileCache became File)
    * @return Object depending on parameters
    */
    static public function create($typeAdapter, $nameAdapterSimple){
        if( !preg_match('@^[A-Z][a-zA-Z0-9-]*@', $nameAdapterSimple) ){
            throw new \InvalidArgumentException('Adapter name is invalid');
        }

        $class = __CLASS__ . '\\' . $typeAdapter . '\\' . $nameAdapterSimple . $typeAdapter;
        return new $class();
    }
}