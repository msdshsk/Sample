<?php

namespace Shsk\Image\Controller\Traits\GD;

use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Image\Creator\GD\TrueColor;
use Shsk\Image\Controller\GD as Controller;

trait Trimming
{
    public function trimming(Size $size, Coordinate $coord)
    {
        $copy = (new TrueColor($size->width, $size->height))->create();
        $im_cpy = $copy->getResource();
        $im_src = $this->getResource();

        imagealphablending($im_cpy, true);
        imagesavealpha($im_cpy, true);
        imagecopyresampled(
            $im_cpy,
            $im_src,
            0,
            0,
            $coord->x,
            $coord->y,
            $size->width,
            $size->height,
            $size->width,
            $size->height
        );

        return $copy;
    }
}
