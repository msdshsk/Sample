<?php

namespace Shsk\Image\Controller\Traits\GD;

use Shsk\Color\RGB as Color;

trait Outline
{
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
