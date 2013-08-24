<?php
use \Draeli\RssBridge\Utils;

if( defined('DEBUG') ){
    if( DEBUG ){
        echo '<link href="css/debug.css" rel="stylesheet" />'; // Un css n'est pas censé se trouver dans le body mais tous les navigateurs le supportent :)

        $strSystem =  'Other' ;
        if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ){
            $strSystem = 'Windows';
        }
        elseif( strtoupper(substr(PHP_OS, 0, 3)) === 'LIN' ){
            $strSystem = 'Linux';
        }

        echo '<div id="debug">';

        echo '<h1>Debug</h1>';

        echo '<section>';
        $arrIncludedFile = get_included_files();
        $intIncludedFileLng = count($arrIncludedFile);
        echo '<h2>Fichiers inclus (' . $intIncludedFileLng . ')</h2>';
        if( $intIncludedFileLng > 0 ){
            echo '<ul class="listDetail">';
            foreach($arrIncludedFile as $fileName){
                echo '<li>' . $fileName . '</li>';
            }
            echo '</ul>';
        }
        echo '</section>';

        if( $strSystem == 'Linux' ){
            echo '<section>';
            $arrGetRusage = getrusage();
            $intGetRusageLng = count($arrGetRusage);
            echo '<h2>Resources</h2>';
            if( $intGetRusageLng > 0 ){
                echo '<ul class="listDetail">';
                foreach($arrGetRusage as $key => $getRusage){
                    echo '<li>' . $key . ' => ' . $getRusage . '</li>';
                }
                echo '</ul>';
            }
            echo '</section>';
        }

        echo '<section>';
        echo '<h2>Memory</h2>';
        echo 'Limit : ' . ini_get('memory_limit') . '<br />';
        $intMemoryUsage = memory_get_usage();
        $strMemoryUsageFormated = Utils::byteToFormatByte($intMemoryUsage);
        echo 'Usage : ' . ( $strMemoryUsageFormated === false ? $intMemoryUsage : $strMemoryUsageFormated ) . '<br />';
        $intMemoryPeakUsage = memory_get_peak_usage();
        $strMemoryPeakUsageFormated = Utils::byteToFormatByte($intMemoryPeakUsage);
        echo 'Max usage : ' . ( $strMemoryPeakUsageFormated === false ? $intMemoryPeakUsage : $strMemoryPeakUsageFormated ) . '<br />';
        echo '</section>';

        echo '<section>';
        echo '<h2>Divers</h2>';
        echo 'OS : ' . $strSystem . '<br />';
        echo 'Inode script : ' . getmyinode() . '<br />';
        echo 'GID script owner : ' . getmygid() . '<br />';
        echo 'Current PHP process number : ' . getmypid() . '<br />';
        echo 'Current script owner name : ' . get_current_user() . '<br />';
        echo 'UID current script owner : ' . getmyuid() . '<br />';
        echo '</section>';

        echo '</div>';
    }
    else{
        die('Oh, poor boy, DEBUG did\'n open his eyes !');
    }
}
else{
    die('Serioulsly ?!'); // $loader = require __DIR__ . '/../app/autoload.php'; // Use this on your main script to use this page.
}