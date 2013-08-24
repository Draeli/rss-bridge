<?php
namespace Draeli\RssBridge\Adapter\Bridge;

use Draeli\RssBridge\Html,
    Draeli\RssBridge\Item;

/**
* RssBridgeTwitter 
* Based on https://github.com/mitsukarenai/twitterbridge-noapi
*
* @name Twitter Bridge
* @description Returns user timelines or keyword/hashtag search results (without using their API).
* @use1(q="keyword or hashtag")
* @use2(u="username")
*/
class TwitterBridge extends \Draeli\RssBridge\BridgeAbstract{
    public function collectData(){
        $parameter = $this->getParameter();

        if( isset($parameter['q']) ){ // keyword search mode
            $html = Html::getFromUrl('http://twitter.com/search/realtime?q='.urlencode($parameter['q']).'+include:retweets&src=typd') or Html::returnError('No results for this query.', 404);
        }
        elseif( isset($parameter['u']) ){ // user timeline mode
            $html = Html::getFromUrl('http://twitter.com/'.urlencode($parameter['u'])) or Html::returnError('Requested username can\'t be found.', 404);
        }
        else{
            Html::returnError('You must specify a keyword (?q=...) or a Twitter username (?u=...).');
        }

        foreach($html->find('div.tweet') as $tweet) {
            $item = new Item();
            $item->username = trim(substr($tweet->find('span.username', 0)->plaintext, 1));	// extract username and sanitize
            $item->fullname = $tweet->getAttribute('data-name'); // extract fullname (pseudonym)
            $item->avatar = $tweet->find('img', 0)->src;	// get avatar link
            $item->id = $tweet->getAttribute('data-tweet-id');	// get TweetID
            $item->uri = 'https://twitter.com'.$tweet->find('a.details', 0)->getAttribute('href');	// get tweet link
            $item->timestamp = $tweet->find('span._timestamp', 0)->getAttribute('data-time');	// extract tweet timestamp
            $item->content = str_replace('href="/', 'href="https://twitter.com/', strip_tags($tweet->find('p.tweet-text', 0)->innertext, '<a>'));	// extract tweet text
            $item->title = $item->fullname . ' (@'. $item->username . ') | ' . $item->content;
            $this->addItem($item);
        }
    }

    public function getName(){
        return 'Twitter Bridge';
    }

    public function getURI(){
        return 'http://twitter.com';
    }

    public function getCacheDuration(){
        return 300; // 5 minutes
    }
}