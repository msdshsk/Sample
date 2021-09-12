<?php

namespace Sample\Coordinate;

use Sample\Property\Coordinate;
use Sample\Property\Size;

class Calculator
{
    const LEFT_TOP = 'leftTop';
    const RIGHT_TOP = 'rightTop';
    const LEFT_BOTTOM = 'leftBottom';
    const RIGHT_BOTTOM = 'rightBottom';

    protected $screen;
    protected $target;
    private $returnCoordinate = self::LEFT_TOP;
    public function __construct(Size $screen, Size $target)
    {
        $this->screen = $screen;
        $this->target = $target;
    }

    protected function changeReturnCoordinate($returnCoordinate)
    {
        $this->returnCoordinate = $returnCoordinate;
    }

    public function setReturnCoordinate($returnCoordinate)
    {
        $this->changeReturnCoordinate($returnCoordinate);
    }

    protected function returnCoordinate($x, $y)
    {
        switch ($this->returnCoordinate) {
            case self::LEFT_BOTTOM:
                $y += $this->target->height - 1;
                break;
            case self::RIGHT_BOTTOM:
                $x += $this->target->width - 1;
                $y += $this->target->height - 1;
                break;
            case self::RIGHT_TOP:
                $x += $this->target->width - 1;
                break;
        }

        return new Coordinate($x, $y);
    }

    public function leftBottom(): Coordinate
    {
        $x = 0;
        $y = $this->screen->height - 1;

        return $this->returnCoordinate($x, $y);
    }

    public function rightBottom(): Coordinate
    {
        $x = $this->screen->width - 1 - $this->target->width;
        $y = $this->screen->height - 1 - $this->target->height;

        return $this->returnCoordinate($x, $y);
    }

    public function center(): Coordinate
    {
        $widthS = $this->screen->width - 1 - ($this->target->width);
        $heightS = $this->screen->height - 1 - ($this->target->height);

        $x = ($widthS / 2);
        $y = ($heightS / 2);

        return $this->returnCoordinate($x, $y);
    }

    public function bottomCenter(): Coordinate
    {
        $widthS = $this->screen->width - 1 - ($this->target->width);
        $x = ($widthS / 2);
        $y = $this->height - $this->target->height;

        return $this->returnCoordinate($x, $y);
    }

    public function topCenter(): Coordinate
    {
        $widthS = $this->screen->width - 1 - ($this->target->width);
        $x = ($widthS / 2);
        $y = 0;

        return $this->returnCoordinate($x, $y);
    }

    public function leftTop(): Coordinate
    {
        $x = 0;
        $y = 0;

        return $this->returnCoordinate($x, $y);
    }

    public function rightTop(): Coordinate
    {
        $x = $this->screen->width - 1 - $this->target->width;
        $y = 0;

        return $this->returnCoordinate($x, $y);
    }
}
