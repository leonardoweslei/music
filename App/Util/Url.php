<?php
namespace Music\Util;

class Url
{
    public static function getBasePath()
    {
        $file = $_SERVER['SCRIPT_FILENAME'];

        return substr($file, 0, strlen($file) - strlen(strrchr($file, "/")) + 1);
    }

    public static function getBaseUrl()
    {
        if (isset($_SERVER['REDIRECT_BASE'])) {
            $root = $_SERVER['REDIRECT_BASE'];
        } else {
            $root = $_SERVER['PHP_SELF'];

            if (substr($root, -3) == "php") {
                $root = explode("/", $root);
                array_pop($root);
                $root = implode("/", $root);
            }
        }

        $root = preg_replace("/\/{2,}/", "/", $root);

        $root = "/" . trim($root, "/") . "/";

        return $root;
    }

    public static function identifyParameters()
    {
        $root = Url::getBasePath();

        if (isset($_SERVER['REDIRECT_URL'])) {
            $part = $_SERVER['REDIRECT_URL'];
        } else {
            $part = $_SERVER['REQUEST_URI'];
        }

        $request = $_SERVER['DOCUMENT_ROOT'] . $part;

        $param = trim(str_replace($root, '', $request), '/');

        if ($param != "") {
            //$param = explode("/", $param);
            $param = preg_split("/[\/\?&]/", $param);
        } else {
            $param = array();
        }

        return $param;
    }

    public function redirect($location)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            return false;
        }

        if (!headers_sent()) {
            header("Location: {$location}");
        } else {
            echo '<script>window.location.href="' . $location . '";</script>';
        }
        exit;
    }

    public static function getFile($storageManager, $hash, $fileType)
    {
        $info = $storageManager->getInfo($hash);

        $fullLength = $info['size'];
        $start      = 0;
        $end        = $fullLength - 1;
        $S3Headers  = array();

        $header    = array();
        $headers[] = 'Accept-Ranges: bytes';
        $headers[] = 'Content-type: ' . $fileType;
        $headers[] = 'Cache-Control: must-revalidate, post-check=0, pre-check=0';
        $headers[] = 'Pragma: public';

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (!preg_match('/^bytes=\d*-\d*(,\d*-\d*)*$/', $_SERVER['HTTP_RANGE'], $matches)) {
                $headers[] = 'HTTP/1.1 416 Requested Range Not Satisfiable';
                $headers[] = 'Content-Range: bytes */0';
                self::_outputHeaders($headers);
                exit;
            }

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE']);
            list($start, $end) = explode('-', $range);

            $start = empty($start) ? 0 : $start;
            $end   = empty($end) ? $fullLength - 1 : $end;
            $end   = min($end, $fullLength - 1);

            if ($start > $end) {
                $headers[] = 'HTTP/1.1 416 Requested Range Not Satisfiable';
                $headers[] = 'Content-Range: bytes */0';
                self::_outputHeaders($headers);
                exit;
            }
            $length             = $end - $start + 1;
            $S3Headers['Range'] = "bytes=$start-$end";
            $headers[]          = 'HTTP/1.1 206 Partial Content';
            $headers[]          = "Content-Length: $length";
            $headers[]          = "Content-Range: bytes $start-$end/$length";
        } else {
            $headers[] = "Content-Length: $fullLength";
            $headers[] = "Content-Range: bytes $start-$end/$fullLength";
        }
        $output = $storageManager->getFile($hash, $S3Headers);
        if ($output === false) {
            throw new \Exception('Error loading file');
        }
        self::_outputHeaders($headers);

        echo $output;
    }

    public static function getFileDirectly($file)
    {
        $finfo    = @finfo_open(FILEINFO_MIME_TYPE);
        $fileType = @finfo_file($finfo, $file);
        @finfo_close($finfo);

        $header    = array();
        $headers[] = 'Accept-Ranges: bytes';
        $headers[] = 'Content-type: ' . $fileType;
        $headers[] = 'Cache-Control: must-revalidate, post-check=0, pre-check=0';
        $headers[] = 'Pragma: public';

        $output = @file_get_contents($file);

        if ($output === false) {
            throw new \Exception('Error loading file');
        }

        self::_outputHeaders($headers);

        echo $output;
    }

    private static function _outputHeaders($headers)
    {
        foreach ($headers as $header) {
            header($header);
        }
    }
}
