<?php

namespace Sample\Color;

use Sample\Color\RGB as Color;

class Calculator
{
    private $color;
    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    public function diff(Color $target)
    {
        $diff = abs($this->color->red - $target->red);
        $diff += abs($this->color->green - $target->green);
        $diff += abs($this->color->blue - $target->blue);

        return $diff;
    }

    public function contrast()
    {
        return (($this->color->red * 299) + ($this->color->green * 587) + ($this->color->blue * 114)) / 1000;
    }
}
