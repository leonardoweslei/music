<?php


namespace Music\Lib;

use Guzzle\Http\Client;
use MusicBrainz\Filters\ArtistFilter;
use MusicBrainz\Filters\LabelFilter;
use MusicBrainz\Filters\RecordingFilter;
use MusicBrainz\Filters\ReleaseGroupFilter;
use MusicBrainz\HttpAdapters\GuzzleHttpAdapter;
use MusicBrainz\MusicBrainz;
use MusicBrainz\Release;

class MusicBrainzSearch
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
        $artistsFounded = array();

        $artistsFounded[$artistName] = $artistName;

        $args = array(
            "artist" => $artistName
        );

        try {
            $releaseArtists = $this->brainz->search(new Release($args));

            if (count($releaseArtists) > 0) {
                foreach ($releaseArtists as $artist) {
                    if ($artist->getScore() > 60) {
                        $name = $artist->getName();

                        $artistsFounded[$name] = $name;
                    }
                }
            }
        } catch (\Exception $e) {
        }

        return array_values($artistsFounded);
    }
}