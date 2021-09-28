<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image\Color;

trait Colors
{
    public function allocate(Color $color)
    {
        return imagecolorallocatealpha($this->im, $color->red, $color->green, $color->blue, $color->alpha);
    }

    public function transparent(Color $color = null): int
    {
        return imagecolortransparent($this->im, $color ? $color->toIndex() : null);
    }

    public function colorat($x, $y)
    {
        $index = imagecolorat($this->im, $x, $y);
        return Color::createFromIndex($index);
    }
}
