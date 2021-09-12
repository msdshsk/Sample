<?php

namespace Sample\Image\Creator\GD;

use Sample\Image\Controller\GD as Controller;

class TrueColor
{
    private $width;
    private $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function create(): Controller
    {
        $im = imagecreatetruecolor($this->width, $this->height);
        return new Controller($im);
    }
}
