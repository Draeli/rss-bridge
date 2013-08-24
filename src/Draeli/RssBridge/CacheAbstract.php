<?php
namespace Draeli\RssBridge;

abstract class CacheAbstract implements \Draeli\RssBridge\CacheInterface{
    protected
        $request,
        $duration
    ;

    /**
    * Request mark
    * Note : il s'agit de l'empreinte de la demande. Autrement dit il s'agit du "nom" qui définit le cache.
    * Example : 'index.php' or 'index.php?paginate=10' or 'http://a-great-url' or 'a-name'
    * @param string $request You can see that like an URI
    * @return this
    */
    public function setRequest($request){
        if( !is_string($request) ){
            throw new \InvalidArgumentException('String must be defined !');
        }

        $this->request = $request;

        return $this;
    }

    /**
    * Get request URI
    * @return string
    */
    public function getRequest(){
        return $this->request;
    }

    /**
    * Cache is prepared ?
    * Note : Cache name is based on request information, then cache must be prepare before use
    * @return \Exception|true
    */
    protected function isPrepareCache(){
        $request = $this->getRequest();
        if( is_null($request) ){
            throw new \Exception('Please feed "setRequest" method before try to load');
        }

        $duration = $this->getDuration();
        if( is_null($duration) ){
            throw new \Exception('Please feed "setDuration" method before try to load');
        }

        return true;
    }

    /**
    * Determines cache name
    * return string
    */
    protected function getCacheName(){
        $this->isPrepareCache();

        $stringToEncode = $this->getRequest();
        return hash('sha1', $stringToEncode);
    }

     /**
    * Define cache duration
    * @param integer $duration Cache duration
    * @return this
    */
    public function setDuration($duration){
        if( !is_integer($duration) ){
            throw new \InvalidArgumentException('Duration must be defined as integer!');
        }

        $this->duration = $duration;

        return $this;
    }

    /**
    * Get cache duration
    * @return integer
    */
    public function getDuration(){
        return $this->duration;
    }

    /**
    * Test if cache expired
    * @return boolean true if expired
    */
    public function isExpired(){
        $time = $this->getTime();
        $duration = $this->getDuration();

        if( $time !== false && ( time() - $duration < $time ) ){ // Cache file has not expired. Serve it.
            return false;
        }

        return true;
    }
}