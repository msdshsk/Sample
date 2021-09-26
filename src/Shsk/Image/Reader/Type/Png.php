<?php

namespace Shsk\Image\Reader\Type;

use Shsk\Image\Controller;

class Png extends TypeAbstract
{
    public function create($filePath): Controller
    {
        $im = imagecreatefrompng($filePath);
        return new Controller($im, $filePath);
    }
}
