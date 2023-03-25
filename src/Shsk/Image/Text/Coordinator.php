<?php

namespace Shsk\Image\Text;

use Shsk\Property\Coordinate;
use Shsk\Coordinate\Calculator;
use Shsk\Property\Size as ScreenSize;
use Shsk\Image\Text\BoundingBox;
use Shsk\Exception\Exception;

class Coordinator extends Calculator
{
    public function __construct(BoundingBox $target, $width = null, $height = null)
    {
        if (!($width instanceof ScreenSize)) {
            $screen = new ScreenSize($width, $height);
        } else {
            $screen = $width;
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
        
        $x = $coord->x - $this->target->spacing;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function bottomCenter(): Coordinate
    {
        $coord = parent::bottomCenter();
        $x = $coord->x - $this->target->spacing;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function topCenter(): Coordinate
    {
        $coord = parent::topCenter();
        $x = $coord->x - $this->target->spacing;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function leftTop(): Coordinate
    {
        $coord = parent::leftTop();
        $x = $coord->x - $this->target->spacing;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function rightTop(): Coordinate
    {
        $coord = parent::rightTop();
        $x = $coord->x;
        $y = $coord->y - $this->target->baseline;

        return new Coordinate($x, $y);
    }

    public function fromCoordinate(Coordinate $coord): Coordinate
    {
        $coord = parent::fromCoordinate($coord);
        $x = $coord->x - $this->target->spacing;
        $y = $coord->y + $this->target->baseline;

        return new Coordinate($x, $y);
    }
}
