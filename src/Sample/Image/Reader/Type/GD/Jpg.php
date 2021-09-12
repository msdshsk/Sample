<?php

namespace Sample\Image\Reader\Type\GD;

use Sample\Image\Controller\GD as Controller;

class Jpg extends \Sample\Image\Reader\Type\GD
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromjpeg($filePath);
        return new Controller($im, $filePath);
    }
}
