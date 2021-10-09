<?php

namespace Shsk\Image\Controller;

use Shsk\Image\Controller;
use Shsk\Property\Coordinate;
use Shsk\Property\Size;
use Shsk\Image;

class Text
{
    private $controller;
    private $text;
    public function __construct(Controller $controller, Image\Text $text)
    {
        $this->controller = $controller;
        $this->text = $text;
    }

    public function write(Coordinate $coord, Image\Color $color)
    {
        return $this->writeText($this->text, $coord, $color);
    }

    private function writeText(Image\Text $text, Coordinate $coord, Image\Color $color)
    {
        if ($color instanceof Image\Color\Picker) {
            $color = $color->pick($this->controller, $text, $coord);
        }

        $index = $this->controller->allocate($color);
        imagettftext(
            $this->controller->getResource(),
            $text->fontSize,
            $text->angle,
            $coord->x,
            $coord->y,
            $index,
            $text->fontPath,
            $text->text
        );

        return $this->controller;
    }

    public function writePos($position, Image\Color $color, int $marginWidth = 0, int $marginHeight = 0)
    {
        $coord = $this->text->coordinate($position, $this->controller->size(), $marginWidth, $marginHeight);

        return $this->writeText($this->text, $coord, $color);
    }

    public function writeFit($position, Image\Color $color, int $margin = 0)
    {
        $size = $this->controller->size()->expandPixcel((-1) * $margin, (-1) * $margin);
        $text = $this->text->fit($size);
        $coord = $this->text->coordinate($position, $this->controller->size());

        return $this->writeText($text, $coord, $color);
    }
}
