<?php
namespace Music\Controller;

use Music\Core\Controller;
use Music\Core\ViewList;
use Music\Model\Album;
use Music\Model\Artist;
use Music\Model\Track;
use Music\Model\Music;

class Import extends Controller
{
    public function legacyAction()
    {
        $arrArtists = array();
        $arrAlbums  = array();
        $arrTracks  = array();

        $cArtists = 0;
        $cAlbums  = 0;
        $cTracks  = 0;

        $songs = Music::find('all', array('order' => 'artist,album,title'));

        foreach ($songs as $song) {
            $artistHash = md5($song->artist);
            $artistName = $song->artist;
            $albumHash  = ($artistHash . $song->album);
            $albumName  = $song->album;
            $trackHash  = ($albumHash . $song->title);
            $trackName  = $song->title;

            if (isset($arrArtists[$artistHash])) {
                $artist = $arrArtists[$artistHash];
            } else {
                $artist = Artist::find('first', array('conditions' => array('name like ?', $artistName)));

                if (empty($artist)) {
                    $artist = Artist::create(array('name' => $artistName));
                    $cArtists++;
                }

                $arrArtists[$artistHash] = $artist;
            }

            if (isset($arrAlbums[$albumHash])) {
                $album = $arrAlbums[$albumHash];
            } else {
                $album = Album::find(
                    'first',
                    array(
                        'conditions' => array(
                            'name like ? and idartist = ?',
                            $albumName,
                            $artist->idartist
                        )
                    )
                );

                if (empty($album)) {
                    $album = Album::create(array('name' => $albumName, 'idartist' => $artist->idartist));
                    $cAlbums++;
                }

                $arrAlbums[$albumHash] = $album;
            }

            if (!isset($arrTracks[$trackHash])) {
                $track = Track::find(
                    'first',
                    array(
                        'conditions' => array(
                            'name like ? and idalbum = ?',
                            $trackName,
                            $album->idalbum
                        )
                    )
                );

                if (empty($track)) {
                    $track = Track::create(
                        array(
                            'name'    => $trackName,
                            'idalbum' => $album->idalbum,
                            'hash'    => $song->hash,
                            'track'   => $song->track,
                        )
                    );
                    $cTracks++;
                }

                $arrTracks[$trackHash] = $track;
            }
        }

        $data           = array();
        $data['header'] = 'Imported data';
        $data['label']  = array(
            'artists' => 'Artists',
            'albums'  => 'Albums',
            'tracks'  => 'Tracks'
        );
        $data['data'][] = array(
            'artists' => $cArtists,
            'albums'  => $cAlbums,
            'tracks'  => $cTracks,
        );

        $this->setViewManager(new ViewList($data));

        return $this->getViewManager();
    }
}