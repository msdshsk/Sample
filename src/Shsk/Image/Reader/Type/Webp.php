<?php

namespace Shsk\Image\Reader\Type;

use Shsk\Image\Controller;

class Webp extends TypeInterface
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromwebp($filePath);
        return new Controller($im, $filePath);
    }
}
