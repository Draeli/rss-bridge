<?php
namespace Draeli\RssBridge\Adapter\Format;

/**
* Json
* Builds a JSON string from $this->items and return it to browser.
*
* @name Json
*/
class JsonFormat extends \Draeli\RssBridge\FormatAbstract{

    public function stringify(){
        // FIXME : sometime content can be null, transform to empty string
        $datas = $this->getDatas();

        return json_encode($datas);
    }

    public function display($debug){
        $this
            ->setContentType('application/json')
            ->callContentType();

        return parent::display($debug);
    }
}