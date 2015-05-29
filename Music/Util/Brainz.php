<?php


namespace Music\Util;

use Guzzle\Http\Client;
use MusicBrainz\Filters\ArtistFilter;
use MusicBrainz\Filters\LabelFilter;
use MusicBrainz\Filters\RecordingFilter;
use MusicBrainz\Filters\ReleaseGroupFilter;
use MusicBrainz\HttpAdapters\GuzzleHttpAdapter;
use MusicBrainz\MusicBrainz;

class Brainz
{
    private $brainz;

    public function __construct()
    {
        $brainz = new MusicBrainz(new GuzzleHttpAdapter(new Client()));
        $brainz->setUserAgent('ApplicationName', '0.2', 'http://example.com');
        $this->brainz = $brainz;
    }

    public function searchArtist($artistName)
    {
        $artistFounded = $artistName;

        $args = array(
            "artist" => $artistName
        );

        try {
            $releaseArtists = $this->brainz->search(new ArtistFilter($args));

            if (count($releaseArtists) > 0) {
                foreach ($releaseArtists as $artist) {
                    if ($artist->getScore() > 98) {
                        break;
                    }
                }

                $artistFounded = $artist->getName();
            }
        } catch (\Exception $e) {
        }

        return $artistFounded;
    }
}