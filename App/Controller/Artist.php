<?php
namespace Music\Controller;

use Music\Config\Config;
use Music\Core\Controller;
use Music\Model\Artist as ArtistModel;
use Music\Util\Url;

class Artist extends Controller
{
    public function listAction()
    {
        $arrArtist = ArtistModel::find('all', array('order' => 'name asc'));

        $this->getViewManager()->setVar('artists', $arrArtist);
        $this->getViewManager()->setViewFile('artist/list.php');

        return $this->getViewManager();
    }

    public function artAction($param)
    {
        $idartist        = $param[0];
        $artist          = ArtistModel::find_by_idartist($idartist);
        $objStoreManager = Config::getStoreManager('image');
        $artistHash      = md5($artist->idartist . $artist->name);
        $info            = $objStoreManager->getInfo($artistHash);

        if (!$info['size']) {
            $param = array(
                'v'   => '1.0',
                'key' => 'AIzaSyALp6qvMuW2uGx1qg_kvmE-UtFPyZvoVqA',
                'q'   => $artist->name . ' filetype:jpg'
            );

            $url = 'https://ajax.googleapis.com/ajax/services/search/images?' . http_build_query($param);

            $json = file_get_contents($url);
            $json = json_decode($json);

            if ($json && $json->responseData && $json->responseData->results) {
                if (count($json->responseData->results) > 0) {
                    foreach ($json->responseData->results as $r) {

                        $pTam = $r->width * 100 / $r->height;

                        if ($r->width == $r->height || ($pTam >= 90 && $pTam <= 110)) {
                            $img = $r->tbUrl;
                            $objStoreManager->saveFile($img, $artistHash);
                            break;
                        }
                    }
                }
            }
        }

        Url::getFile($objStoreManager, $artistHash, "image/jpeg");
        exit();
    }
}