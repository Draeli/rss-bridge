<?php
namespace Draeli\RssBridge\Adapter\Bridge;

use Draeli\RssBridge\Html,
    Draeli\RssBridge\Item;

/**
* RssBridgeFlickrExplore 
* Returns the newest interesting images from http://www.flickr.com/explore
*
* @name Flickr Explore
* @description Returns the latest interesting images from Flickr
*/
class FlickrExploreBridge extends \Draeli\RssBridge\BridgeAbstract{
    public function collectData(){
        $html = Html::getFromUrl('http://www.flickr.com/explore') or Html::returnError('Could not request Flickr.', 404);
    
        foreach($html->find('span.photo_container') as $element){
            $item = new Item();
            $item->uri = 'http://flickr.com'.$element->find('a',0)->href;
            $item->thumbnailUri = $element->find('img',0)->getAttribute('data-defer-src');
            $item->content = '<a href="' . $item->uri . '"><img src="' . $item->thumbnailUri . '" /></a>'; // FIXME: Filter javascript ?
            $item->title = $element->find('a',0)->title;
            $this->addItem($item);
        }
    }

    public function getName(){
        return 'Flickr Explore';
    }

    public function getURI(){
        return 'http://www.flickr.com/explore';
    }

    public function getCacheDuration(){
        return 21600; // 6 hours
    }
}