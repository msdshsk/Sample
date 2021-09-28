<?php

namespace Shsk\Image;

use Shsk\Property\ReadOnly;

class Color extends ReadOnly
{
    public function __construct(int $r, int $g, int $b, int $alpha = 0)
    {
        parent::__construct(['red' => $r, 'green' => $g, 'blue' => $b, 'alpha' => $alpha]);
    }

    public function toColorCode()
    {
        return vsprintf('#%02s%02s%02s', array_map(function ($code) {
            return base_convert($code, 10, 16);
        }, [$this->red, $this->green, $this->blue]));
    }

    public function toIndex()
    {
        $index = $this->alpha << 24;
        $index += $this->red << 16;
        $index += $this->green << 8;
        $index += $this->blue;

        return $index;
    }

    public function change($r=null, $g=null, $b=null, $a=null)
    {
        $r = $r ?? $this->red;
        $g = $g ?? $this->green;
        $b = $b ?? $this->blue;
        $a = $a ?? $this->alpha;

        return new self($r, $g, $b, $a);
    }

    public function withAlpha($a)
    {
        return $this->change(null, null, null, $a);
    }

    public function withRed($r)
    {
        return $this->change($r, null, null, null);
    }

    public function withGreen($g)
    {
        return $this->change(null, $g, null, null);
    }

    public function withBlue($b)
    {
        return $this->change(null, null, $b, null);
    }

    public function diff(Color $target)
    {
        $diff = abs($this->red - $target->red);
        $diff += abs($this->green - $target->green);
        $diff += abs($this->blue - $target->blue);

        return $diff;
    }

    public function contrast()
    {
        return (($this->red * 299) + ($this->green * 587) + ($this->blue * 114)) / 1000;
    }

    public function visibility(Color $target)
    {
        $targetContrast = $target->contrast();
        $baseContrast = $this->contrast();
        $colorDiff = $target->diff($this);
        
        $contrastDiff = abs($baseContrast - $targetContrast);

        if ($colorDiff >= 500 && $contrastDiff >= 125) {
            return true;
        }

        return false;
    }

    public static function createFromIndex($index)
    {
        $a = ($index >> 24) & 0xFF;
        $r = ($index >> 16) & 0xFF;
        $g = ($index >> 8) & 0xFF;
        $b = $index & 0xFF;
        $x = new self($r, $g, $b, $a);
        return new self($r, $g, $b, $a);
    }

    public static function createFromHash($ary)
    {
        $r = isset($ary['red']) ?? 255;
        $g = isset($ary['green']) ?? 255;
        $b = isset($ary['blue']) ?? 255;
        $a = isset($ary['alpha']) ?? 0;

        return new self($r, $g, $b, $a);
    }
}
