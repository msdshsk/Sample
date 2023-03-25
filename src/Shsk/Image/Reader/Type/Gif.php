<?php

namespace Shsk\Image\Reader\Type;

use Shsk\Image\Controller;

class Gif extends TypeAbstract
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromgif($filePath);
        imagepalettetotruecolor($im);
        return new Controller($im, $filePath);
    }
}
