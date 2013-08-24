<?php
namespace Draeli\RssBridge;

interface BridgeInterface{
    /**
    * Implementation of the bridge for catch informations and stored (See documentation in README for details)
    * @param array All parameters required by bridge
    * @return none
    */
    public function collectData();

    /**
    * Get public bridge name
    * @return string
    */
    public function getName();

    /**
    * Get public bridge URI
    * @return string
    */
    public function getURI();

    /**
    * Define duraction for cache
    * @return integer
    */
    public function getCacheDuration();
}