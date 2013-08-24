<?php
/**
* All bridge logic
* Note : adapter are store in other place
*/

namespace Draeli\RssBridge;

use Draeli\Annotation\Annotation;

class Bridge extends Adapter{
    /**
    * Get path directory use for call a Bridge specific class
    * @return string
    */
    static public function getDir(){
        return __DIR__ . '/Adapter/Bridge/';
    }

    /**
    * Read bridge dir and catch informations about each bridge depending annotation
    * @return array Informations about each bridge
    */
    static public function searchInformation(){
        $pathDirBridge = self::getDir();

        $searchCommonPatternTypeSimple = array('description', 'name');
        $listBridge = array();
        $bridges = glob($pathDirBridge . '*Bridge.php', GLOB_NOSORT);
        if( is_array($bridges) ){
            foreach($bridges as $aBridge){
                // Récupération des informations d'annotation pour le bridge
                $annotation = new Annotation($aBridge);
                $informations = $annotation->parse()->getInformation();

                // Transformation des informations d'annotation
                $currentBridge = array();
                $bridgeName = key($informations['class']);
                foreach($searchCommonPatternTypeSimple as $typeSimpleName){
                    if( isset($informations['class'][$bridgeName]['annotation']['simple'][$typeSimpleName]) ){
                        $currentBridge[$typeSimpleName] = $informations['class'][$bridgeName]['annotation']['simple'][$typeSimpleName];
                    }
                }
                foreach($informations['class'][$bridgeName]['annotation']['parameter'] as $parameterName => $parameterDetail){
                    if( preg_match('@use[0-9]+@', $parameterName) ){
                        $currentBridge['use'][] = $parameterDetail;
                    }
                }
                $listBridge[substr($bridgeName, 0, -6)] = $currentBridge;
            }
        }

        return $listBridge;
    }
}