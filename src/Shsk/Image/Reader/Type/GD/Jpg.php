<?php

namespace Shsk\Image\Reader\Type\GD;

use Shsk\Image\Controller\GD as Controller;
use Shsk\Image\Reader\Type\GD as Type;

class Jpg extends Type
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromjpeg($filePath);
        return new Controller($im, $filePath);
    }
}
