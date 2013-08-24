<?php
namespace Draeli\RssBridge\Adapter\Bridge;

use Draeli\RssBridge\Html,
    Draeli\RssBridge\Item;

/**
* RssBridgeIdentica 
*
* @name Identica Bridge
* @description Returns user timelines
* @use1(u="username")
*/
class IdenticaBridge extends \Draeli\RssBridge\BridgeAbstract{
    public function collectData(){
        $parameter = $this->getParameter();

        if (isset($parameter['u'])) {   /* user timeline mode */
            $html = Html::getFromUrl('https://identi.ca/'.urlencode($parameter['u'])) or Html::returnError('Requested username can\'t be found.', 404);
        }
        else {
            Html::returnError('You must specify an Identica username (?u=...).');
        }

        foreach($html->find('li.major') as $dent) {
            $item = new Item();
            $item->uri = html_entity_decode($dent->find('a', 0)->href);	// get dent link
            $item->timestamp = strtotime($dent->find('abbr.easydate', 0)->plaintext);	// extract dent timestamp
            $item->content = trim($dent->find('div.activity-content', 0)->innertext);	// extract dent text
            $item->title = $parameter['u'] . ' | ' . $item->content;
            $this->addItem($item);
        }
    }

    public function getName(){
        return 'Identica Bridge';
    }

    public function getURI(){
        return 'https://identica.com';
    }

    public function getCacheDuration(){
        return 300; // 5 minutes
    }
}
