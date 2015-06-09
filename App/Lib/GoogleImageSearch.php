<?php
namespace Music\Lib;

class GoogleImageSearch
{
    public static function search($keywords)
    {
        $imageURL = "";
        $param    = array(
            'v'   => '1.0',
            'key' => 'AIzaSyALp6qvMuW2uGx1qg_kvmE-UtFPyZvoVqA',
            'q'   => $keywords
        );

        $url = 'https://ajax.googleapis.com/ajax/services/search/images?' . http_build_query($param);

        $json = file_get_contents($url);
        $json = json_decode($json);

        if ($json && $json->responseData && $json->responseData->results) {
            if (count($json->responseData->results) > 0) {
                foreach ($json->responseData->results as $r) {

                    $pTam = $r->width * 100 / $r->height;

                    if ($r->width == $r->height || ($pTam >= 90 && $pTam <= 110)) {
                        $imageURL = $r->tbUrl;
                        break;
                    }
                }
            }
        }

        return $imageURL;
    }
}
