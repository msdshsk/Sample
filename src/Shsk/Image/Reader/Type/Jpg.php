<?php

namespace Shsk\Image\Reader\Type;

use Shsk\Image\Controller;

class Jpg extends TypeAbstract
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromjpeg($filePath);
        return new Controller($im, $filePath);
    }
}
