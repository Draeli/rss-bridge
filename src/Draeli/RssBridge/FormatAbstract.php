<?php
namespace Draeli\RssBridge;

abstract class FormatAbstract implements FormatInterface{
    const DEFAULT_CHARSET = 'UTF-8';

    private
        $contentType,
        $charset,
        $datas,
        $extraInfos
    ;

    /**
    * Define charset
    * @param string $charset
    * @return this
    */
    public function setCharset($charset){
        if( !is_string($charset) ){
            throw new \InvalidArgumentException('String must be defined !');
        }

        $this->charset = $charset;

        return $this;
    }

    /**
    * Get charset
    * @return string
    */
    public function getCharset(){
        $charset = $this->charset;

        return is_null($charset) ? self::DEFAULT_CHARSET : $charset;
    }

    /**
    * Define header 'Content-Type'
    * @param string $contentType 'Content-Type'
    * @return this
    */
    protected function setContentType($contentType){
        if( !is_string($contentType) ){
            throw new \InvalidArgumentException('String must be defined !');
        }

        $this->contentType = $contentType;

        return $this;
    }

    /**
    * Call header 'Content-Type'
    * @return none
    */
    protected function callContentType(){
        header('Content-Type: ' . $this->contentType);
    }

    /**
    * Display the raw information
    * @return this
    */
    public function display($debug = false){
        // force header to display raw result
        if( $debug ){
            header('Content-Type: text/plain;charset=' . $this->getCharset());
        }

        echo $this->stringify();

        return $this;
    }

    /**
    * Define datas to use
    * @param array $datas Datas
    * @return this
    */
    public function setDatas(array $datas){
        $this->datas = $datas;

        return $this;
    }

    /**
    * Return defined datas
    * @return array
    */
    public function getDatas(){
        if( !is_array($this->datas) ){
            throw new \LogicException('Feed the ' . get_class($this) . ' with "setDatas" method before !');
        }

        return $this->datas;
    }

    /**
    * Define common informations can be required by formats and set default value for unknow values
    * @param array $extraInfos array with know informations (there isn't merge !!!)
    * @return this
    */
    public function setExtraInfos(array $extraInfos = array()){    
        foreach(array('name', 'uri') as $infoName){
            if( !isset($extraInfos[$infoName]) ){
                $extraInfos[$infoName] = '';
            }
        }

        $this->extraInfos = $extraInfos;

        return $this;
    }

    /**
    * Return extra infos
    * @return array See "setExtraInfos" detail method to know what extra are disponibles
    */
    public function getExtraInfos(){
        if( is_null($this->extraInfos) ){ // No extra info ?
            $this->setExtraInfos(); // Define with default value
        }

        return $this->extraInfos;
    }
    
    /**
     * Sanitized html while leaving it functionnal.
     * The aim is to keep html as-is (with clickable hyperlinks)
     * while reducing annoying and potentially dangerous things.
     * Yes, I know sanitizing HTML 100% is an impossible task.
     * Maybe we'll switch to http://htmlpurifier.org/
     * or http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/index.php
     */
    public function sanitizeHtml($html)
    {
        $html = str_replace('<script','<&zwnj;script',$html); // Disable scripts, but leave them visible.
        $html = str_replace('<iframe','<&zwnj;iframe',$html);
        $html = str_replace('<link','<&zwnj;link',$html);
        // We leave alone object and embed so that videos can play in RSS readers.
        return $html;
    }
}