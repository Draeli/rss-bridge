<?php
namespace Draeli\RssBridge;

class Utils{
    /**
    * Define personnalized error handler
    * Note : see documentation http://php.net/manual/en/function.set-error-handler.php
    */
    static public function set_error_handler($errno, $errstr, $errfile, $errline){
        // error was suppressed with the @ operator
        if( 0 === error_reporting() ){
            return false;
        }

        // http://www.php.net/manual/fr/reserved.variables.httpresponseheader.php
        // if( strpos($errstr, 'file_get_contents') === 0 ){}

        throw new \ErrorException('Error occur for "' . $errstr . '" in "' . $errfile . '" line "' .$errline . '"', 0, $errno);
    }

    /**
    * Convert byte to human byte format
    * @param integer $intByte Integer to convert
    * @param integer $intPrecision Precision
    * @return false|string Converted value or false in error case
    */
    static public function byteToFormatByte($intByte, $intPrecision = 2){
        $arrUnit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        if( $intByte < 0 ){
            return false;
        }

        $intByte = max($intByte, 0);
        $floatPow = floor(($intByte ? log($intByte) : 0) / log(1024));
        $floatPow = min($floatPow, count($arrUnit) - 1); 

        $intByte /= pow(1024, $floatPow);

        return round($intByte, $intPrecision) . $arrUnit[$floatPow];
    }

    /**
    * Convert human/PHP byte format to byte
    * @param string $strFormatedByte String to convert
    * @return false|integer Converted value or false in error case
    */
    static public function FormatByteToByte($strFormatedByte){
        $arrUnit = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        preg_match('@^ *([0-9]+) *([KMGTPEZY])?B? *$@Ui', $strFormatedByte, $arrOut);
        if( empty($arrOut) ){
            return false;
        }

        $intValue = $arrOut[1];

        // To avoid problem with PHP shorthandbyte (http://fr2.php.net/manual/en/faq.using.php#faq.using.shorthandbytes)
        $strUnit = (isset($arrOut[2]) ? strtoupper($arrOut[2]) : '') . 'B';

        return $intValue * pow(1024, $arrUnit[$strUnit]);
    }
}