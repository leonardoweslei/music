<?php
namespace Music\Controller;

use Music\Config\Config;
use Music\Core\Controller;
use Music\Core\ViewList;
use Music\Lib\GoogleImageSearch;
use Music\Lib\Image;
use Music\Lib\ImageManager;
use Music\Lib\LastFmSearch;
use Music\Lib\MusicBrainzSearch;
use Music\Model\Artist as ArtistModel;
use Music\Model\Album as AlbumModel;
use Music\Util\Url;

class Track extends Controller
{
    public function listAction($param)
    {
        $idArtist = $param[0];

        $idAlbum  = $param[0];
        $album    = AlbumModel::find_by_idalbum($idAlbum);
        $artist   = ArtistModel::find_by_idartist($album->idartist);
        $arrTrack = AlbumModel::find(
            'all',
            array(
                'conditions' => array('idalbum = ?', $idAlbum),
                'order'      => 'name asc'
            )
        );

        $_arrTrack = [];

        foreach ($arrTrack as $track) {
            $track               = $track->to_array();
            $track['artistName'] = $artist->name;
            $track['albumName']  = $album->name;
            $_arrTrack[]         = $track;
        }

        $arrTrack = $_arrTrack;

        $data           = array();
        $data['header'] = $artist->name . ' - ' . $album->name . '\'s tracks';
        $data['label']  = array(
            'name' => 'Name',
            'artistName'  => 'Artist',
            'albumName'  => 'Album'
        );
        $data['data'] = $arrTrack;

        $this->setViewManager(new ViewList($data));


        $this->getViewManager()->setVar('artist', $artist);
        $this->getViewManager()->setVar('album', $album);
        $this->getViewManager()->setVar('tracks', $arrTrack);
        $this->getViewManager()->setViewFile('track/list.php');

        return $this->getViewManager();
    }

    public function artAction($param)
    {
        $error           = false;
        $idAlbum         = $param[0];
        $album           = AlbumModel::find_by_idalbum($idAlbum);
        $artist          = ArtistModel::find_by_idartist($album->idartist);
        $objStoreManager = Config::getStoreManager('image');
        $albumHash       = md5($artist->idartist . $artist->name . $album->idalbum);
        $info            = $objStoreManager->getInfo($albumHash);

        if (!$info['size']) {
            $objLastFmSearch = new LastFmSearch();

            $imageURL = GoogleImageSearch::search($artist->name);

            if ($imageURL) {
                $imageURL = ImageManager::resize($imageURL);
                $objStoreManager->saveFile($imageURL, $albumHash);
            } else {
                $error = true;
            }
        }

        try {
            Url::getFile($objStoreManager, $albumHash, "image/jpeg");
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