<?php
// This file check if install allow rss-bridge use

$loader = require __DIR__ . '/../app/autoload.php';

date_default_timezone_set('UTC');

if( DEBUG ){
    $errors = array(
        'ERROR' => array(),
        'WARNING' => array(),
    );

    if( ini_get('allow_url_fopen') != 1 ){
        $errors['ERROR'][] = 'You must set allow_url_fopen to true. http://fr2.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen';
    }

    if( !extension_loaded('mbstring') ){
        $errors['ERROR'][] = 'Actually we don\'t provide callback for manage without this extension';
    }

    if( !extension_loaded('openssl') ){
        $errors['WARNING'][] = 'Some bridges use HTTPS. You can try run without activated without warranty.';
    }
}
else{
    die('Please active DEBUG before try !');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Rss-bridge" />
        <title>RSS-Bridge : check server capacity</title>
        <link href="css/style.css" rel="stylesheet">
        <style type="text/css">
            body{padding:0 1rem;}
            h1{font-size:2rem;}
            ol{list-style:decimal;margin-left:1rem;}
        </style>
    </head>

    <body>

        <header>
            <h1>RSS-Bridge : check server capacity</h1>
        </header>

        <?php if( count($errors['ERROR']) == 0 && count($errors['WARNING']) == 0 ): ?>
        <p>
            Yeah baby, your server "Roxx du poney" ! :) You can run Rss-bridge.
        </p>
        <?php else: ?>

        <?php if( count($errors['ERROR']) > 0 ): ?>
        <section>
            <ol>
                <?php foreach( $errors['ERROR'] as $anError ): ?>
                <li>
                    <?php echo $anError ?>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>
        <?php endif; ?>

        <?php if( count($errors['WARNING']) > 0 ): ?>
        <section>
            <ol>
                <?php foreach( $errors['WARNING'] as $anError ): ?>
                <li>
                    <?php echo $anError ?>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>
        <?php endif; ?>

        <?php endif; ?>

    </body>
</html>