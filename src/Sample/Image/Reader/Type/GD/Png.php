<?php

namespace Sample\Image\Reader\Type\GD;

use Sample\Image\Controller\GD as Controller;

class Png extends \Sample\Image\Reader\Type\GD
{
    public function create($filePath): Controller
    {
        $im = imagecreatefrompng($filePath);
        return new Controller($im, $filePath);
    }
}
