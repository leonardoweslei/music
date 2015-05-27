<?php
namespace Music\Lib\Storage;

use Music\Lib\Song;

class Local implements IStorage
{
    var $storageDir;

    function __construct($storageDir)
    {
        $this->storageDir = $storageDir;

        if (!file_exists($storageDir)) {
            mkdir($storageDir);
        }
    }

    function saveFile($filePath, $hash = false)
    {
        if (!$hash) {
            $parts = Song::getFileParts($filePath);
            $hash  = $parts['hash'];
        }

        if ($hash === false) {
            return false;
        }

        $newFilePath = $this->storageDir . $hash;

        if (file_exists($newFilePath)) {
            unlink($newFilePath);
        }

        if (!copy($filePath, $newFilePath)) {
            return false;
        }

        return true;
    }

    function getFile($hash, $headers)
    {
        $filePath = $this->getFilePath($hash);

        if (!file_exists($filePath)) {
            return false;
        }

        return file_get_contents($filePath);
    }

    function getFilePath($hash)
    {
        $hash     = str_replace('.mp3', '', $hash);
        $filePath = $this->storageDir . DIRECTORY_SEPARATOR . $hash;

        return $filePath;
    }

    function getInfo($hash)
    {
        $filePath     = $this->getFilePath($hash);
        $info         = pathinfo($filePath);
        $info['size'] = @filesize($filePath);

        return $info;
    }
}
