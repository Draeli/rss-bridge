<?php
namespace Draeli\RssBridge;

interface FormatInterface{
    /**
    * Prepare string result
    */
    public function stringify();

    /**
    * Manage display result
    * @param boolean $debug To display raw result
    */
    public function display($debug);

    /**
    * Define datas to use
    */
    public function setDatas(array $bridge);
}