<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Image\Controller;
use Shsk\Image\Color;

class Svg extends TypeAbstract
{
    public function saveAs($filePath)
    {
        return file_put_contents($filePath, $this->toSvg($filePath));
    }

    public function save()
    {
        return $this->saveAs($this->getFilePath());
    }

    public function output()
    {
        return $this->toSvg();
    }

    private function toSvg($filePath = null)
    {
        $filePath = $filePath ?? $this->getFilePath();
        $ctrl = new Controller($this->getResource());
        $width = $ctrl->width();
        $height = $ctrl->height();
        $svg = sprintf(
            '<svg version="%s" id="%s" xmlns="%s" xmlns:xlink="%s" width="%spx" height="%spx" viewBox="0 0 %s %s">',
            '1.0',
            pathinfo($this->getFilePath(), PATHINFO_BASENAME),
            'http://www.w3.org/2000/svg',
            'http://www.w3.org/1999/xlink',
            $width,
            $height,
            $width,
            $height
        );
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = $ctrl->colorat($x, $y);
                $svg .= sprintf('<rect fill="%s" x="%s" y="%s" width="1" height="1"/>', $color->toColorCode(), $x, $y);
            }
        }
        $svg .= '</svg>';

        return $svg;
    }
}
