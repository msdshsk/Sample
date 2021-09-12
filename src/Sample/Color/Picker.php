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
    const COLOR_THRESHOLD = 140;
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

    public function average(Controller $ctrl): Color
    {
        $copy = $ctrl->copy();
        $copy->filter(IMG_FILTER_GRAYSCALE);

        $height = $copy->height();
        $width = $copy->width();
        $max = $width * $height;
        
        $r = 0;
        $g = 0;
        $b = 0;
        $total = 0;
        foreach (range(0, $width - 1) as $x) {
            foreach (range(0, $height -1) as $y) {
                $rgb = $copy->colorat($x, $y);
                $total += $rgb->red;
            }
        }
        
        $avgColor = floor($total / $max);
        $rgb = new Color($avgColor, $avgColor, $avgColor);
        $copy->destroy();
        return $rgb;
    }

    public function createColor(): Color
    {
        $ctrl = $this->createSamplingImage();

        $avg = $this->average($ctrl);
        $pick = ($avg->red + $avg->green + $avg->blue) / 3;
        
        //$ajast = 100;
        if ($pick < self::COLOR_THRESHOLD) {
            $r = 255;
            $g = 255;
            $b = 255;
        } else {
            $r = 0;
            $g = 0;
            $b = 0;
        }

        $clean = array_map(function ($n) {
            if ($n > 255) {
                return 255;
            }
            if ($n < 0) {
                return 0;
            }
            return $n;
        }, [$r, $g, $b]);
        
        return new Color($clean[0], $clean[1], $clean[2]);
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
