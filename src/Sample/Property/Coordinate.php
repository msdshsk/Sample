<?php

namespace Sample\Property;

class Coordinate extends ReadOnly
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
}
