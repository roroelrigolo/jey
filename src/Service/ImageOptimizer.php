<?php
namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private const MAX_WIDTH_USER = 200;
    private const MAX_HEIGHT_USER = 300;

    private const MAX_WIDTH_PRODUCT = 600;
    private const MAX_HEIGHT_PRODUCT = 800;

    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resize(string $type, string $filename): void
    {
        list($iwidth, $iheight) = getimagesize($filename);
        $ratio = $iwidth / $iheight;
        if($type == "user"){
            var_dump("user");
            $width = self::MAX_WIDTH_USER;
            $height = self::MAX_HEIGHT_USER;
        }
        elseif ($type == "product"){
            var_dump("product");
            $width = self::MAX_WIDTH_PRODUCT;
            $height = self::MAX_HEIGHT_PRODUCT;
        }
        else {
            $width = self::MAX_WIDTH_PRODUCT;
            $height = self::MAX_HEIGHT_PRODUCT;
        }

        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }
}