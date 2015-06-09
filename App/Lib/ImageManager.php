<?php
namespace Music\Lib;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class ImageManager
{
    public static function resize($filePath)
    {
        $tmpPath  = sys_get_temp_dir();
        $tmpName  = uniqid(time());
        $tmpNamex = $tmpPath . DIRECTORY_SEPARATOR . $tmpName . '_.jpg';
        $tmpName  = $tmpPath . DIRECTORY_SEPARATOR . $tmpName . '.jpg';

        $imagine = new Imagine();

        $imagine->open($filePath)->save($tmpName);
        $imagine->open($tmpName)->save($tmpNamex);

        $image = $imagine->open($tmpName);
        $image->resize(new Box(250, 250))
            ->effects()
            ->gamma(3)
            ->grayscale()->
            blur();
        $image->save($tmpName);

        $watermark = $imagine->open($tmpNamex);
        $wSize     = $watermark->getSize();
        $wSize     = $wSize->heighten(250);

        if ($wSize->getWidth() > 250) {
            $wSize = $wSize->widen(250);
        }

        $watermark->resize($wSize)->save($tmpNamex);

        $size = $image->getSize();

        $bottomRight = new Point(
            ($size->getWidth() - $wSize->getWidth()) / 2,
            ($size->getHeight() - $wSize->getHeight()) / 2
        );

        $image->paste($watermark, $bottomRight)->save($tmpName);

        return $tmpName;
    }
}