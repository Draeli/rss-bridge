<?php
namespace Draeli\RssBridge;

$loader = require __DIR__ . '/../app/autoload.php'; // Load configuration and need stuff in autoload mode

try{
    if( isset($_REQUEST) && isset($_REQUEST['action']) ){
        switch($_REQUEST['action']){
            case 'display':
                if( isset($_REQUEST['bridge']) ){
                    $nameBridge = $_REQUEST['bridge'];
                    $nameFormat = $_REQUEST['format'];

                    $nsAdapter = __NAMESPACE__ . '\\Adapter'; // Because I'm lazy ? :D

                    $bridge = $nsAdapter::create('Bridge', $nameBridge); // Prepare a new bridge

                    if( !DEBUG ){ // Cache desactive when DEBUG is active
                        $cache = $nsAdapter::create('Cache', 'File'); // We create a cache under 'File' mode
                        $cache->setDuration($bridge->getCacheDuration()); // Define cache duration
                        Html::setCache($cache); // Comment this lign for avoid cache use
                    }

                    // Data retrieval
                    $bridge
                        ->setParameter($_REQUEST) // Set specific parameters needed by the bridge
                        ->collectData(); // Run all stuff to collect data.

                    // Data transformation
                    $format = $nsAdapter::create('Format', $nameFormat);
                    $format
                        ->setDatas($bridge->getDatas()) // Datas to convert
                        ->setExtraInfos(array( // Set extra information
                            'name' => $bridge->getName(),
                            'uri' => $bridge->getURI(),
                        ))
                        ->display(DEBUG);
                    die;
                }
                break;
        }
    }
}
catch(\ErrorException $e){
    die( DEBUG ? $e->getMessage() : 'Oups ! An error occur.' );
}
catch(HttpException $e){
    header('HTTP/1.1 ' . $e->getCode() . ' ' . Http::getMessageForCode($e->getCode()));
    header('Content-Type: text/plain');
    die($e->getMessage());
}
catch(\Exception $e){
    die($e->getMessage());
}

function getHelperButtonFormat($value, $name){
    return '<button type="submit" name="format" value="' . $value . '">' . $name . '</button>';
}

$bridges = Bridge::searchInformation();
// echo '<pre>';
// var_export($bridges);
// echo '</pre>';
$formats = Format::searchInformation();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Rss-bridge" />
        <title>RSS-Bridge</title>
        <link href="css/style.css" rel="stylesheet" />
        <!--[if IE]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>

        <header>
            <h1>RSS-Bridge</h1>
        </header>

        <?php foreach($bridges as $bridgeReference => $bridgeInformations): ?>
        <section id="bridge-<?php echo $bridgeReference ?>" data-ref="<?php echo $bridgeReference ?>">
            <h2><?php echo $bridgeInformations['name'] ?></h2>
            <p class="description">
                <?php echo isset($bridgeInformations['description']) ? $bridgeInformations['description'] : 'No description provided' ?>
            </p> 

            <?php if( isset($bridgeInformations['use']) && count($bridgeInformations['use']) > 0 ): ?>
            <ol class="list-use">
                <?php foreach($bridgeInformations['use'] as $anUseNum => $anUse): ?>
                <li data-use="<?php echo $anUseNum ?>">
                    <form method="GET" action="?">
                        <input type="hidden" name="action" value="display" />
                        <input type="hidden" name="bridge" value="<?php echo $bridgeReference ?>" />
                        <?php foreach($anUse as $arg): ?>
                        <?php $idArg = 'arg-' . $bridgeReference . '-' . $anUseNum . '-' . $arg['name']; ?>
                        <?php if( is_string($arg['value']) ): ?>
                        <input id="<?php echo $idArg ?>" type="text" value="" placeholder="<?php echo htmlentities($arg['value']) ?>" name="<?php echo $arg['name'] ?>" />
                        <?php else: ?>
                        <input type="hidden" name="<?php echo $arg['name'] ?>" value="<?php echo $arg['value'] ?>" />
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php foreach( $formats as $name => $infos ): ?>
                            <?php if( isset($infos['name']) ){ echo getHelperButtonFormat($name, $infos['name']); } ?>
                        <?php endforeach; ?>
                    </form>
                </li>
                <?php endforeach; ?>
            </ol>
            <?php else: ?>
            <form method="GET" action="?">
                <input type="hidden" name="action" value="display" />
                <input type="hidden" name="bridge" value="<?php echo $bridgeReference ?>" />
                <?php foreach( $formats as $name => $infos ): ?>
                    <?php if( isset($infos['name']) ){ echo getHelperButtonFormat($name, $infos['name']); } ?>
                <?php endforeach; ?>
            </form>
            <?php endif; ?>
        </section>
        <?php endforeach; ?>

        <footer>
            <a href="https://github.com/sebsauvage/rss-bridge">RSS-Bridge</a> <?php echo API_VERSION ?>
        </footer>

        <?php
            if( DEBUG ){
                require __DIR__ . '/debug.php';
            }
        ?>
    </body>
</html>