<?php
define('DEBUG', false); // true for debugging only
define('API_VERSION', 'alpha 0.3'); // true for debugging only

date_default_timezone_set('UTC');

if( DEBUG ){
    ini_set('display_errors', '1');
    error_reporting(-1);
}
else{
    error_reporting(0);
}

$loader = require __DIR__ . '/../vendor/autoload.php';

$configFile = __DIR__ . '/config.php';
if( !file_exists($configFile) ){ // Le fichier pour les configurations spécifiques du serveur n'existe pas, on le créé
    if( !copy(__DIR__ . '/config-basic.php', $configFile) ){
        throw new \Exception('Config copy fail !');
    }
}

require $configFile;

return $loader;