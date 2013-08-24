<?php
namespace Draeli\RssBridge\Adapter\Format;

/**
* Plaintext
* Returns $this->items as raw php data.
*
* @name Plaintext
*/
class PlaintextFormat extends \Draeli\RssBridge\FormatAbstract{

    public function stringify(){
        $datas = $this->getDatas();
        return print_r($datas, true);
    }

    public function display($debug){
        $this
            ->setContentType('text/plain;charset=' . $this->getCharset())
            ->callContentType();

        return parent::display($debug);
    }
}