<?php

namespace Sample\Image\Reader\Type\GD;

use Sample\Image\Controller\GD as Controller;

class Webp extends \Sample\Image\Reader\Type\GD
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromwebp($filePath);
        return new Controller($im, $filePath);
    }
}
