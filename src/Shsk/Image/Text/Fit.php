<?php

namespace Shsk\Image\Text;

use Shsk\Property\Coordinate;
use Shsk\Coordinate\Calculator;
use Shsk\Property\Size as ScreenSize;
use Shsk\Image\Text\Size as TargetSize;

class Fit extends Calculator
{
    public function __construct(TargetSize $target, int|ScreenSize $width = null, int $height = null)
    {
        if (!($width instanceof ScreenSize)) {
            $screen = new ScreenSize($width, $height);
        }
        parent::__construct($screen, $target);
        $this->changeReturnCoordinate(self::LEFT_BOTTOM);
    }

    public function leftBottom(): Coordinate
    {
        $coord = parent::leftBottom();

        $x = $coord->x - $this->target->spacing;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function rightBottom(): Coordinate
    {
        $coord = parent::rightBottom();
        $x = $coord->x;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function center(): Coordinate
    {
        $coord = parent::center();
        
        $x = $coord->x - ($this->target->spacing);
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function bottomCenter(): Coordinate
    {
        $coord = parent::bottomCenter();
        $x = $coord->x - ($this->target->spacing);
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function topCenter(): Coordinate
    {
        $coord = parent::topCenter();
        $x = $coord->x - ($this->target->spacing);
        $y = $coord->y + $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function leftTop(): Coordinate
    {
        $coord = parent::leftTop();
        $x = $coord->x - ($this->target->spacing);
        $y = $coord->y + $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function rightTop(): Coordinate
    {
        $x = $this->width - $this->target->width;
        $y = $this->size->height + $this->target->baseline;

        return new Coordinate($x, $y);
    }
}
