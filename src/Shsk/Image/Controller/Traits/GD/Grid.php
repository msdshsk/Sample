<?php

namespace Shsk\Image\Controller\Traits\GD;

use Shsk\Color\RGB as Color;

trait Grid
{
    public function drawGrid(int $span, Color $color)
    {
        $size = $this->size();

        $rows = $size->height / $span;
        $cols = $size->width / $span;

        $index = $this->allocate($color);
        $im = $this->getResource();

        imagesetthickness($im, 1);

        for ($i = 1; $i < $rows; $i++) {
            $row = $i * $span;
            imageline($im, $row, 0, $row, $size->height, $index);
        }

        for ($i = 1; $i < $cols; $i++) {
            $col = $i * $span;
            imageline($im, 0, $col, $size->width, $col, $index);
        }
        return true;
    }
}
