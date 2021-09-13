<?php

namespace Shsk\Image\Reader\Type\GD;

use Shsk\Image\Controller\GD as Controller;
use Shsk\Image\Reader\Type\GD as Type;

class Png extends Type
{
    public function create($filePath): Controller
    {
        $im = imagecreatefrompng($filePath);
        return new Controller($im, $filePath);
    }
}
