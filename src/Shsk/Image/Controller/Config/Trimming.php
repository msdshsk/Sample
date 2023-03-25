<?php

namespace Shsk\Image\Controller\Config;

use Shsk\Property\Size;
use Shsk\Property\ReadOnlyProperty;
use Shsk\Exception\Exception;
use Shsk\Property\Coordinate;

class Trimming extends ReadOnlyProperty
{
    public function __construct($ary)
    {
        $size = $ary['size'] ?? null;
        $height = $ary['height'] ?? null;
        $width = $ary['width'] ?? null;

        $coord = $ary['coord'] ?? null;
        $x = $ary['x'] ?? 0;
        $y = $ary['y'] ?? 0;

        switch (true) {
            case $size instanceof Size:
                break;
            case $height === null && $width === null:
                throw new Exception("Undefined property. 'height' or 'width'");
            default:
                $size = new Size($width, $height);
                break;
        }

        switch (true) {
            case $coord instanceof Coordinate:
                break;
            default:
                $coord = new Coordinate($x, $y);
                break;
        }

        parent::__construct(compact(['size', 'coord']));
    }
}
