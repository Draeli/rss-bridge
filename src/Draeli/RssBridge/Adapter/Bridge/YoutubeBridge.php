<?php
namespace Draeli\RssBridge\Adapter\Bridge;

use Draeli\RssBridge\Html,
    Draeli\RssBridge\Item;

/**
* RssBridgeYoutube 
* Returns the newest videos
*
* @name Youtube Bridge
* @description Returns the newest videos
* @use1(u="username")
*/
class YoutubeBridge extends \Draeli\RssBridge\BridgeAbstract{
    public function collectData(){
        $parameter = $this->getParameter();

        if( isset($parameter['u']) ){
            $html = Html::getFromUrl('https://www.youtube.com/user/'.urlencode($parameter['u']).'/videos') or Html::returnError('Could not request Youtube.', 404);
        }
        else{
            Html::returnError('You must specify an username (?u=...).');
        }
    
        foreach($html->find('li.channels-content-item') as $element) {
            $item = new Item();
            $item->uri = 'https://www.youtube.com'.$element->find('a',0)->href;
            $item->thumbnailUri = 'https:'.$element->find('img',0)->src;
            $item->title = trim($element->find('h3',0)->plaintext);
            $item->content = '<a href="' . $item->uri . '"><img src="' . $item->thumbnailUri . '" /></a><br><a href="' . $item->uri . '">' . $item->title . '</a>';
            $this->addItem($item);
        }
    }

    public function getName(){
        return 'Youtube Bridge';
    }

    public function getURI(){
        return 'https://www.youtube.com/';
    }

    public function getCacheDuration(){
        return 21600; // 6 hours
    }
}
