<?php
namespace Music\Controller;

use Music\Config\Config;
use Music\Core\Controller;
use Music\Lib\GoogleImageSearch;
use Music\Lib\Image;
use Music\Lib\ImageManager;
use Music\Lib\LastFmSearch;
use Music\Lib\MusicBrainzSearch;
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
        $error           = false;
        $idartist        = $param[0];
        $artist          = ArtistModel::find_by_idartist($idartist);
        $objStoreManager = Config::getStoreManager('image');
        $artistHash      = md5($artist->idartist . $artist->name);
        $info            = $objStoreManager->getInfo($artistHash);

        if (!$info['size']) {
            $imageURL        = "";
            $objLastFmSearch = new LastFmSearch();
            $artistImages    = $objLastFmSearch->searchArtist($artist->name);

            if (!empty($artistImages['image'])) {
                foreach ($artistImages['image'] as $imageURL) {
                    if ($imageURL['size'] == "extralarge") {
                        break;
                    }
                }

                $imageURL = $imageURL['#text'];
            }

            if (!$imageURL) {
                $imageURL = GoogleImageSearch::search($artist->name);
            }

            if ($imageURL) {
                $imageURL = ImageManager::resize($imageURL);
                $objStoreManager->saveFile($imageURL, $artistHash);
            } else {
                $error = true;
            }
        }

        try {
            Url::getFile($objStoreManager, $artistHash, "image/jpeg");
        } catch (\Exception $e) {
            $error = true;
        }

        if ($error) {
            $file = Config::getRootPath() . "default/disc.png";
            Url::getFileDirectly($file);
        }

        exit();
    }
}