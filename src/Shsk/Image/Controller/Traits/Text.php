<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image;
use Shsk\Property\Coordinate;

trait Text
{
    public function writeText(Image\Text $text, Coordinate $coord, Image\Color $color)
    {
        $index = $this->allocate($color);
        return imagettftext($this->getResource(), $text->fontSize, $text->angle, $coord->x, $coord->y, $index, $text->fontPath, $text->text);
    }
}