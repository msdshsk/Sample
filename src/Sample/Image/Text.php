<?php

namespace Sample\Image;

use Sample\Color\RGB as Color;
use Sample\BoundingBox\Font;

class Text extends \Sample\Property\ReadOnly
{
    public function __construct($text, $fontSize, $fontPath, $angle = 0)
    {
        parent::__construct([
            'text' => $text,
            'fontSize' => $fontSize,
            'fontPath' => $fontPath,
            'angle' => $angle,
        ]);
    }

    public function changeText($text)
    {
        return new self($text, $this->fontSize, $this->fontPath, $this->angle);
    }

    public function changeFontSize($fontSize)
    {
        return new self($this->text, $fontSize, $this->fontPath, $this->angle);
    }

    public function changeFontPath($fontPath)
    {
        return new self($this->text, $this->fontSize, $fontPath, $this->angle);
    }

    public function changeAngle($angle)
    {
        return new self($this->text, $this->fontSize, $this->fontPath, $angle);
    }
}
