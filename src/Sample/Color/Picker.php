<?php

namespace Sample\Color;

use Sample\Property\ReadOnly;
use Sample\Image\Reader\Factory\GD as ReaderFactory;
use Sample\Image\Controller\GD as Controller;
use Sample\Image\Creator\GD\TrueColor as Creator;
use Sample\Color\RGB as Color;
use Sample\Property\Size as CommonSize;
use Sample\Property\Coordinate;
use Sample\Image\Text\BoundingBox\TTF as BoundingBox;
use Sample\Image\Controller\Config\Resize as ResizeConfig;
use Sample\Exception\Exception;

class Picker
{
    const SAMPLE_WIDTH = 16;
    const COLOR_THRESHOLD = 127;
    private $im;
    private $imagePath;
    private $controller;
    private $textSize;
    private $textCoord;
    public function __construct($imagePath = null, Controller $ctrl = null)
    {
        $this->imagePath = $imagePath;
        if ($ctrl instanceof Controller) {
            $this->controller = $ctrl;
            $this->imagePath = $imagePath;
        } elseif ($imagePath !== null) {
            $this->controller = $this->loadImage($this->imagePath);
        } else {
            new Exception("can't load imageFile or imageController");
        }
    }

    public static function createFromController(Controller $ctrl)
    {
        return new self(null, $ctrl);
    }

    private function createSamplingImage()
    {
        $orgImg = $this->controller;
        $trimImg = null;
        
        if ($this->textSize instanceof CommonSize && $this->textCoord instanceof Coordinate) {
            $trimImg = $orgImg->trimming($this->textSize, $this->textCoord);
        }

        $config = new ResizeConfig(['width' => self::SAMPLE_WIDTH]);

        if ($trimImg !== null) {
            $resizedImg = $trimImg->resize($config);
            $trimImg->destroy();
        } else {
            $resizedImg = $orgImg->resize($config);
        }

        return $resizedImg;
    }

    public function setTextSize(CommonSize $size, Coordinate $coord)
    {
        $this->textSize = $size;

        $x = $coord->x;
        $y = $coord->y - $size->height;

        $this->textCoord = new Coordinate($x, $y);
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
    
    public function createColor(): Color
    {
        $ctrl = $this->createSamplingImage();

        $base = $this->average($ctrl, false);

        $pick = floor(($base->red + $base->green + $base->blue) / 3);
        if ($pick < self::COLOR_THRESHOLD) {
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
                $func($base->red, $i, $up),
                $func($base->green, $i, $up),
                $func($base->blue, $i, $up)
            );
            if ($this->visibility($target, $base)) {
                break;
            }
        }

        return $target;
    }

    public function visibility(Color $target, Color $base)
    {
        $baseCalc = new Calculator($base);
        $targetCalc = new Calculator($target);
        $targetContrast = $targetCalc->contrast();
        $colorDiff = $targetCalc->diff($base);
        $baseContrast = $baseCalc->contrast();
        $contDiff = abs($baseContrast - $targetContrast);
        if ($colorDiff >= 500 && $contDiff >= 125) {
            return true;
        }

        return false;
    }

    protected function loadImage($imagePath): Controller
    {
        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
        $factory = new ReaderFactory($ext);
        
        $reader = $factory->create();
        return $reader->create($imagePath);
    }

    public function __destruct()
    {
        $this->controller->destroy();
    }
}
