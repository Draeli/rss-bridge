<?php
namespace Draeli\RssBridge;

use Draeli\RssBridge\Utils;

/**
* Class dedicated for call HTML
* Note : cette classe est mise en place en prévision d'une implémentation futur d'autres éléments (fait de manière sale mais suffisante)
*/
class Html{
    static protected $cache = null;

    /**
    * Defined cache object to use
    * @param CacheAbstract|null $cache Defined cache type use or null if there's not cache
    */
    static public function setCache(CacheAbstract $cache = null){
        static::$cache = $cache;
    }

    /**
    * Get defined object cache
    * @return CacheAbstract|null That's not 'CacheAbstract' exactly but a class which extend
    */
    static public function getCache(){
        return static::$cache;
    }

    /**
    * Get SimpleDom object on URL demand
    * Note : initial protype =>
      ($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
      Transparent re-implementation
    */
    static public function getFromUrl(){
        $args = func_get_args();

        $cacheIsExpired = true; // By default we assume no cache then never expire (then cache always expire !)

        $cache = static::getCache();
        if( !is_null($cache) && isset($args[0]) && is_string($args[0]) ){ // Cache defined ?
            $cacheIsExpired = $cache->setRequest($args[0])->isExpired();
        }

        set_error_handler(array('\Draeli\RssBridge\Utils', 'set_error_handler'));

        $argsLng = count($args);

        if( $cacheIsExpired ){
            if( !isset($args[1]) ){
                $args[1] = false;
            }

            if( !isset($args[2]) ){
                $randVersion = rand(23, 30) . '.' . rand(0, 9);

                // http://www.php.net/manual/fr/context.http.php
                // http://en.wikipedia.org/wiki/List_of_HTTP_header_fields
                $context = stream_context_create(array(
                    'http' => array(
                        'header' =>
                            'Accept-Charset: utf-8' . "\r\n" . 
                            'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:' . $randVersion . ') Gecko/' . date('Ymd') . ' Firefox/' . $randVersion . "\r\n", // À la bourrin ! :)
                    )
                ));
                $args[2] = $context;
            }

            $argsFileGetContens = $argsLng > 5 ? array_slice($args, 0, 5) : $args;
            /*
            Note : il est possible d'utilisater les options de context, cf. http://www.php.net/manual/fr/context.http.php + http://fr2.php.net/manual/en/function.file-get-contents.php
            */
            $sourceHtml = call_user_func_array('\file_get_contents', $argsFileGetContens );
        }
        else{ // Cache not expired, we reload stuff
            $sourceHtml = $cache->loadData();
        }

        if( $sourceHtml === false ){
            throw new \Exception('No data to load from this URL');
        }

        $argsStrGetContens = $argsLng > 5 ? array_slice($args, 5) : array();
        array_unshift($argsStrGetContens, $sourceHtml);

        $result = call_user_func_array( array('\Sunra\PhpSimple\HtmlDomParser', 'str_get_html'),  $argsStrGetContens);
        // $result = call_user_func_array( array('\Sunra\PhpSimple\HtmlDomParser', 'file_get_html'), $args );
        restore_error_handler ();

        if( !is_null($cache) ){ // Cache expired or not exist, we refresh cache
            $cache->saveData($sourceHtml);
        }

        return $result;
    }

    // FIXME : to factorize with getFromUrl
    /**
    * Actually better idea don't use this !!!
    */
    static public function getFromString(){
        set_error_handler(array('\Draeli\RssBridge\Utils', 'set_error_handler'));
        $result = call_user_func_array( array('\Sunra\PhpSimple\HtmlDomParser', 'str_get_html'), func_get_args() );
        restore_error_handler ();

        return $result;
    }

    /**
    * Launch probative exception
    * @param string $message Error message
    * @param integer $code HTTP Code (Default : 400 for Bad request)
    */
    static public function returnError($message, $code = 400){
        throw new \Draeli\RssBridge\HttpException($message, $code);
    }

    /**
    * Take URL string for image and prepare them to be encode in base64
    * Note : cf. http://en.wikipedia.org/wiki/Data_URI_scheme
    * @param string $url
    * @return string
    */
    static public function imgToData64($url){
        $validImgFormat = array('jpeg' => true, 'jpg' => true, 'png' => true);
        $urlLng = strlen($url);
        $ext = null;
        if( $urlLng > 2 ){
            $ext = substr($url, -3);
        }

        if( !is_null($ext) && isset($validImgFormat[$ext]) ){
            var_dump($url);
            $contents = file_get_contents($url);
            $base64 = base64_encode($contents);
            return 'data:image/' . $ext . ';base64,' . $base64;
        }

        return $url;
    }
}