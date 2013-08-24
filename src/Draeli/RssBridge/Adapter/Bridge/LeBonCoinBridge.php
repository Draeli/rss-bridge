<?php
namespace Draeli\RssBridge\Adapter\Bridge;

use Draeli\RssBridge\Html,
    Draeli\RssBridge\Item;

/**
* LeBonCoinBridge
*
* @name leboncoin.fr
* @description Retourne les offres ou demandes pour des critères spécifiques
* @use1(use=1,q="vous cherchez ?",location="ville ou CP",ps="prix minimum",pe="prix maximum")
* @use2(use=2,q="ils offrent ?",location="ville ou CP",ps="prix minimum",pe="prix maximum")
*/
class LeBonCoinBridge extends \Draeli\RssBridge\BridgeAbstract{
    public function collectData(){
        $parameter = $this->getParameter();

        // Type d'annonce
        $typeAnnonce = 'offres';
        if( isset($parameter['use']) && $parameter['use'] == '2' ){
            $typeAnnonce = 'demandes';
        }

        $saveImage = true;
        // if( isset($parameter['image']) ){ // FIXME : ajouter le système de config avant de remettre en place
            // $saveImage = true;
        // }

        // FIXME : 35 résultats par page *1 = 35 résultats (au delà pour l'instant ca me claque entre les doigts dans certains cas, voir pourquoi)
        for($i = 1; $i < 2; $i++){
            // Préparation des paramètres
            $urlParam = array(
                'sp' => 0, // Trier par date
                'o' => $i, // Page 1 par défaut
            );
            foreach(array('q', 'location', 'ps', 'pe') as $key){
                if( isset($parameter[$key]) && !empty($parameter[$key]) ){
                    $urlParam[$key] = urlencode($parameter[$key]);
                }
            }

            if( isset($parameter['q']) ){   /* keyword search mode */
                $html = Html::getFromUrl('http://www.leboncoin.fr/annonces/' . $typeAnnonce . '/?' . http_build_query($urlParam)) or Html::returnError('No results for this query.', 404);
            }
            else{
                Html::returnError('You must specify a keyword (?q=...).');
            }

            $elParent = $html->find('.list-lbc a');
            if( !is_null($elParent) ){
                foreach($elParent as $anElement){
                    $item = new Item();

                    $date = utf8_encode(trim($anElement->find('.date', 0)->plaintext));

                    $item->uri = $anElement->href;
                    $item->title = utf8_encode(trim($anElement->find('.detail .title', 0)->plaintext)) . ' (' . $date . ')';

                    // Récupération du content
                    $htmlContent = Html::getFromUrl($item->uri) or Html::returnError('No results for this query.', 404);
                    $htmlContentParent = $htmlContent->find('.lbcContainer', 0);
                    // $item->content = $anElement->find('.detail .title', 0)->plaintext);

                    // var_dump($htmlContentParent);
                    $imgStyle = $htmlContentParent->find('.print-image1 img', 0);
                    $imgStyle = is_object($imgStyle) ? '<img height="140" src="' . $imgStyle->src . '" alt="Première image proposé" />' : '';

                    $content = '';
                    $tableDetail = $htmlContentParent->find('.lbcParams tbody tr');
                    foreach($tableDetail as $aTr){
                        $label = trim($aTr->find('th', 0)->plaintext);
                        $content .= utf8_encode($label) . ' ' . utf8_encode((trim($aTr->find('td', 0)->plaintext))) . '<br />'; // Note : le utf8_encode est là car la page renvoi du 'windows-1252'
                    }
                    $description = $htmlContentParent->find('.AdviewContent .content', 0);
                    $description = is_object($description) ? utf8_encode(trim($description->plaintext)) . '<br />' : '';

                    $item->content = $content . $description . ($saveImage ? $imgStyle : '');

                    $this->addItem($item);

                    usleep(50); // Stop l'exécution du programme quelques millisecondes afin d'éviter de bourriner leur serveur
                }
            }
        }
    }

    public function getName(){
        return 'leboncoin.fr search';
    }

    public function getURI(){
        return 'http://www.leboncoin.fr';
    }

    public function getCacheDuration(){
        return 600; // 10 minutes
    }
}