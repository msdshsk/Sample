<?php

namespace Shsk\Image\Reader\Type\GD;

use Shsk\Image\Controller\GD as Controller;
use Shsk\Image\Reader\Type\GD as Type;

class Webp extends Type
{
    public function create($filePath): Controller
    {
        $im = imagecreatefromwebp($filePath);
        return new Controller($im, $filePath);
    }
}
