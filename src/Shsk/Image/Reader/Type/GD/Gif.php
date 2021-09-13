<?php

namespace Shsk\Image\Reader\Type\GD;

use Shsk\Image\Controller\GD as Controller;

class Gif extends \Sample\Image\Reader\Type\GD
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromgif($filePath);
        return new Controller($im, $filePath);
    }
}
