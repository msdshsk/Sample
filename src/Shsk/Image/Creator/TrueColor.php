<?php

namespace Shsk\Image\Creator;

use Shsk\Image\Controller;
use Shsk\Image\Color;

class TrueColor
{
    private $width;
    private $height;
    private $baseColor;

    public function __construct($width, $height, Color $baseColor = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->baseColor = $baseColor;
    }

    public function create(): Controller
    {
        $im = imagecreatetruecolor($this->width, $this->height);
        $ctrl = new Controller($im);
        if ($this->baseColor !== null) {
            $ctrl->fill(0, 0, $this->baseColor);
        }
        return $ctrl;
    }
}
