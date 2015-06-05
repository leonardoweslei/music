<?php
namespace Music\Util;

class String
{
    public static function slugify($str)
    {
        $str = strtolower(trim(self::stripAccents($str)));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);

        return preg_replace('/-+/', "-", $str);
    }

    public static function snippetChars($phrase, $start, $max_chars, $delimiter = " &hellip;")
    {
        if (strlen($phrase) > $max_chars) {
            $phrase = trim(substr($phrase, $start, $max_chars), "., ") . $delimiter;
        }

        return $phrase;
    }

    public static function isUtf8($str)
    {
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c > 247)) {
                    return false;
                } elseif ($c > 239) {
                    $bytes = 4;
                } elseif ($c > 223) {
                    $bytes = 3;
                } elseif ($c > 191) {
                    $bytes = 2;
                } else {
                    return false;
                }

                if (($i + $bytes) > $len) {
                    return false;
                }

                while ($bytes > 1) {
                    $i++;
                    $b = ord($str[$i]);

                    if ($b < 128 || $b > 191) {
                        return false;
                    }

                    $bytes--;
                }
            }
        }

        return true;
    }

    public static function utf8Decode($str)
    {
        return self::isUtf8($str) ? utf8_decode($str) : $str;
    }

    public static function utf8Encode($str)
    {
        return utf8_encode(self::utf8Decode($str));
    }

    public static function stripAccents($str, $case = 'normal')
    {
        $str = trim($str);

        if (ctype_digit($str)) {
            return $str;
        } else {
            $accents     = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig|tilde);/';
            $str         = self::utf8Encode($str);
            $str_encoded = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
            $str         = preg_replace($accents, '$1', $str_encoded);
            $replace     = array('([\40])', '([^a-zA-Z0-9-])', '(-{2,})');
            $with        = array('-', '', '-');
            $str         = preg_replace($replace, $with, $str);
        }

        if ($case == "upper") {
            return strtoupper($str);
        } elseif ($case == "lower") {
            return strtolower($str);
        } else {
            return $str;
        }
    }

    public static function snippetWords($str, $max_words, $max_chars = false, $delimiter = " &hellip;")
    {
        $phrase_array = explode(" ", $str);

        if (count($phrase_array) > $max_words && $max_words > 0) {
            $str_temp = implode(" ", array_slice($phrase_array, 0, $max_words));

            while ($max_chars && strlen($str_temp) > $max_chars) {
                $max_words -= 1;
                $str_temp = implode(" ", array_slice($phrase_array, 0, $max_words));
            }

            $str = $str_temp . $delimiter;
        }

        return $str;
    }

    public static function utf8DecodeArr($dat)
    {
        if (is_string($dat)) {
            return self::utf8Decode($dat);
        }

        if (!is_array($dat)) {
            return $dat;
        }

        $ret = array();

        foreach ($dat as $i => $d) {
            $ret[$i] = self::utf8DecodeArr($d);
        }

        return $ret;
    }
}
