<?php
define('DEBUG', false); // true for debugging only
define('API_VERSION', 'alpha 0.2'); // true for debugging only

date_default_timezone_set('UTC');

if( DEBUG ){
    ini_set('display_errors', '1');
    error_reporting(-1);
}
else{
    error_reporting(0);
}

return require __DIR__ . '/../vendor/autoload.php';