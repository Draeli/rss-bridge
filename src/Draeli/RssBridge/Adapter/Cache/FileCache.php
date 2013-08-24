<?php
namespace Draeli\RssBridge\Adapter\Cache;

use Draeli\RssBridge\Item;

/**
* Cache with file system
*/
class FileCache extends \Draeli\RssBridge\CacheAbstract{
    protected $cacheDirCreated; // boolean to avoid always check dir cache existence

    public function loadData(){
        return file_get_contents($this->getCacheFile());
    }

    public function saveData($datas){
        if( !is_string($datas) ){
            throw new \InvalidArgumentException('Actually "saveData" under ' . __CLASS__ . ' only support string $datas');
        }

        file_put_contents($this->getCacheFile(), $datas);

        return $this;
    }

    /**
    * Return file modification time or false if there's not cache
    * @return (timestamp Unix)|false
    */
    public function getTime(){
        $cacheFile = $this->getCacheFile();
        if( file_exists($cacheFile) ){
            return filemtime($cacheFile);
        }

        return false;
    }

    /**
    * Return cache path (and create if not exist)
    * @return string Cache path
    */
    protected function getCachePath(){
        $cacheDir = getcwd() . '/../cache/'; // FIXME : configuration ? (dirty hack where I suppose we run under web/ directory)

        // FIXME : implement recursive dir creation
        if( is_null($this->cacheDirCreated) && !is_dir($cacheDir) ){
            $this->cacheDirCreated = true;

            mkdir($cacheDir,0705);
            chmod($cacheDir,0705);
        }

        return $cacheDir;
    }

    /**
    * Get the file name use for cache store
    * @return string Path to the file cache
    */
    protected function getCacheFile(){
        return $this->getCachePath() . $this->getCacheName();
    }

    /**
    * Determines cache name
    * return string
    */
    protected function getCacheName(){
        return parent::getCacheName() . '.cache';
    }
}