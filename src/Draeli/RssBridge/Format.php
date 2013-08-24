<?php
namespace Draeli\RssBridge;

use Draeli\Annotation\Annotation;

/**
* All format logic
* Note : adapter are store in other place
*/
class Format extends Adapter{
    /**
    * Get path directory use for call a Format specific class
    * @return string
    */
    static public function getDir(){
        return __DIR__ . '/Adapter/Format/';
    }

    /**
    * Read format dir and catch informations about each format depending annotation
    * @return array Informations about each format
    */
    static public function searchInformation(){
        $pathDirFormat = self::getDir();

        $searchCommonPatternTypeSimple = array('name');
        $listFormat = array();
        $formats = glob($pathDirFormat . '*Format.php', GLOB_NOSORT);
        if( is_array($formats) ){
            foreach($formats as $aFormat){
                // Récupération des informations d'annotation pour le format
                $annotation = new Annotation($aFormat);
                $informations = $annotation->parse()->getInformation();

                // Transformation des informations d'annotation
                $currentFormat = array();
                $formatName = key($informations['class']);
                foreach($searchCommonPatternTypeSimple as $typeSimpleName){
                    if( isset($informations['class'][$formatName]['annotation']['simple'][$typeSimpleName]) ){
                        $currentFormat[$typeSimpleName] = $informations['class'][$formatName]['annotation']['simple'][$typeSimpleName];
                    }
                }
                $listFormat[substr($formatName, 0, -6)] = $currentFormat;
            }
        }

        return $listFormat;
    }
}