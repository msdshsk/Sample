<?php

namespace Shsk\Property;

use Shsk\Coordinate\Calculator;
use Shsk\Coordinate\Calulator;

class Coordinate extends ReadOnlyProperty
{
    public function __construct($x, $y)
    {
        parent::__construct(['x' => $x, 'y' => $y]);
    }

    public function max()
    {
        return $this->x > $this->y ? $this->x : $this->y;
    }

    public function min()
    {
        return $this->x < $this->y ? $this->x : $this->y;
    }

    public function ajast(int $width, int $height)
    {
        $ajast_x = $this->x + $width;
        $ajast_y = $this->y + $height;

        return new self($ajast_x, $ajast_y);
    }
}
