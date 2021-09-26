<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image\Color;

trait Draw
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
            // imageline($im, $row, 0, $row, $size->height, $index);

            imageline($im, 0, $row, $size->width - 1, $row, $index);
        }

        for ($i = 1; $i < $cols; $i++) {
            $col = $i * $span;
            // imageline($im, 0, $col, $size->width, $col, $index);
            imageline($im, $col, 0, $col, $size->height - 1, $index);
        }
        return true;
    }

    public function drawOutline(int $weight, Color $color)
    {
        $size = $this->size();

        $im = $this->getResource();
        imagesetthickness($im, $weight);

        $round = round($weight / 2);
        $floor = floor($weight / 2);

        $x1 = $floor;
        $y1 = $floor;
        $x2 = $size->width - $round;
        $y2 = $size->height - $round;
        
        $index = $this->allocate($color);
        
        // left vertical
        imageline($im, $x1, 0, $x1, $size->height - 1, $index);

        // top horizon
        imageline($im, $x1, $y1, $x2, $y1, $index);

        // right vertical
        imageline($im, $x2, 0, $x2, $size->height - 1, $index);

        // bottom horizon
        return imageline($im, $x1, $y2, $x2, $y2, $index);
    }
}
