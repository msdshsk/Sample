<?php

namespace Shsk\Image\Reader\Type\GD;

use Shsk\Image\Controller\GD as Controller;
use Shsk\Image\Reader\Type\GD as Type;

class Gif extends Type
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromgif($filePath);
        return new Controller($im, $filePath);
    }
}
