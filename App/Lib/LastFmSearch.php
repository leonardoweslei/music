<?php


namespace Music\Lib;

use Dandelionmood\LastFm\LastFm;
use Music\Config\Config;

class LastFmSearch
{
    private $lastFmApi;

    public function __construct()
    {
        $lastFmApi = new LastFm(
            Config::getConfig('LastFM', 'key'), Config::getConfig('LastFM', 'secret')
        );

        $this->lastFmApi = $lastFmApi;
    }

    public function searchArtist($artistName, $info = array('name', 'image'))
    {
        $ret = array();

        $args = array(
            "artist" => $artistName
        );

        try {
            $result = $this->lastFmApi->artist_getInfo($args);

            if (!empty($result)) {
                $artist = (array)$result->artist;

                foreach ($info as $i) {
                    $ret[$i] = $artist[$i];
                    if (is_array($ret[$i])) {
                        foreach ($ret[$i] as $key => $value) {
                            $ret[$i][$key] = (array)$value;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return $ret;
    }
}