<?php

namespace Shsk\Image\Color;

use Shsk\Image\Controller;
use Shsk\Image\Color;
use Shsk\Image\Text;
use Shsk\Property\Coordinate;

class Picker extends Color
{
    const SAMPLE_WIDTH = 16;
    const COLOR_THRESHOLD = 127;

    public function __construct()
    {
        parent::__construct(0, 0, 0, 0);
    }

    public function pick(Controller $ctrl, Text $text, Coordinate $coord)
    {
        $box = $text->boundingBox();
        $coord = new Coordinate($coord->x, $coord->y - $box->height);
        $trimed = $ctrl->trimming()->trim($box->size(), $coord);
        $resized = $trimed->resize()->byWidth(static::SAMPLE_WIDTH);

        $avgColor = $this->average($resized, false);

        $pick = floor(($avgColor->red + $avgColor->green + $avgColor->blue) / 3);
        if ($pick < static::COLOR_THRESHOLD) {
            $collection = range($pick + 1, 255);
            $up = 1;
        } else {
            $collection = range(0, $pick - 1);
            $up = -1;
        }

        $func = function ($c, $i, $up) {
            $rtn = $c + ($i * $up);
            $rtn = $rtn < 0 ? 0 : $rtn;
            $rtn = $rtn > 255 ? 255 : $rtn;

            return $rtn;
        };

        foreach ($collection as $i) {
            $target = new Color(
                $func($avgColor->red, $i, $up),
                $func($avgColor->green, $i, $up),
                $func($avgColor->blue, $i, $up)
            );
            if ($avgColor->visibility($target)) {
                break;
            }
        }

        $trimed->destroy();
        $resized->destroy();

        return $target;
    }

    public function average(Controller $ctrl, $grayScale = true): Color
    {
        $copy = $ctrl->copy();
        if ($grayScale === true) {
            $copy->filter(IMG_FILTER_GRAYSCALE);
        }

        $height = $copy->height();
        $width = $copy->width();
        $max = $width * $height;
        $r = 0;
        $g = 0;
        $b = 0;
        foreach (range(0, $width - 1) as $x) {
            foreach (range(0, $height -1) as $y) {
                $rgb = $copy->colorat($x, $y);
                $r += $rgb->red;
                $g += $rgb->green;
                $b += $rgb->blue;
            }
        }
        $rAvg = floor($r / $max);
        $gAvg = floor($g / $max);
        $bAvg = floor($b / $max);

        $rgb = new Color($rAvg, $gAvg, $bAvg);
        $copy->destroy();
        return $rgb;
    }
}
