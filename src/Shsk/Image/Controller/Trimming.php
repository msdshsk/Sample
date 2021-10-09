<?php

namespace Shsk\Image\Controller;

use Shsk\Image\Controller;
use Shsk\Property\Coordinate;
use Shsk\Property\Size;
use Shsk\Coordinate\Calculator;

class Trimming
{
    
    private $controller;
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function trim(Size $size, Coordinate $coord)
    {
        $copyCoord = new Coordinate(0, 0);
        $resampled = $this->controller->resample($size, $copyCoord,  $size, $coord);

        return $resampled;
    }

    public function square($coord = Calculator::CENTER)
    {
        $screenSize = $this->controller->size();
        $trimSize = null;

        $min = $screenSize->min();
        $trimSize = new Size($min, $min);

        if (!($coord instanceof Coordinate)) {
            $calc = new Calculator($screenSize, $trimSize);
            $coord = $calc->fromString($coord);
        }
        return $this->trim($trimSize, $coord);
    }
}