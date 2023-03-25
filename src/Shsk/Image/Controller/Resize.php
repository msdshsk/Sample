<?php

namespace Shsk\Image\Controller;

use Shsk\Image\Controller;
use Shsk\Property\Coordinate;
use Shsk\Property\Size;

class Resize
{
    private $controller;
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function byRatio($ratio)
    {
        $size = $this->controller->size()->expand($ratio);
        return $this->byPixcel($size);
    }

    public function byPixcel(Size $size)
    {
        $coord = new Coordinate(0, 0);

        $resampled = $this->controller->resample($size, $coord, $this->controller->size(), $coord);
        return $resampled;
    }

    public function byWidth($width)
    {
        $parsent = $width / $this->controller->width();
        $height = $this->controller->height() * $parsent;

        return $this->byPixcel(new Size($width, $height));
    }

    public function byHeight($height)
    {
        $parsent = $height / $this->controller->height();
        $width = $this->controller->width() * $parsent;

        return $this->byPixcel(new Size($width, $height));
    }
}