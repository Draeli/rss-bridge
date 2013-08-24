<?php
namespace Draeli\RssBridge;

use Draeli\RssBridge\Item;

abstract class BridgeAbstract implements BridgeInterface{
    private
        $items = array(), // Collect datas are stored in this variable
        $param = array()
    ;

    /**
    * Return datas store in the bridge
    * @return array
    */
    public function getDatas(){
        return $this->items;
    }

    /**
    * Add new Item in the Item Collection
    * @param Item $item
    * @return this
    */
    public function addItem(Item $item){
        $this->items[] = $item;

        return $this;
    }

    /**
    * Defined datas with parameters depending choose bridge
    * @param array $param $_REQUEST, $_GET, $_POST, or array with bridge expected paramters
    * @return $this
    */
    public function setParameter(array $param){
        $this->param = $param;

        return $this;
    }

    /**
    * Get parameter defined
    * @return array
    */
    public function getParameter(){
        return $this->param;
    }

    /**
    * Define default duraction for cache
    * @return integer
    */
    public function getCacheDuration(){
        return 3600; // 1 hour
    }
}