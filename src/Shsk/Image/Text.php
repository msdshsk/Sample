<?php

namespace Shsk\Image;

use Shsk\Property\ReadOnly;
use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Image\Text\BoundingBox;
use SHsk\Image\Text\Coordinator;

class Text extends ReadOnly
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

    public function withText($text)
    {
        return new self($text, $this->fontSize, $this->fontPath, $this->angle);
    }

    public function withFontSize($fontSize)
    {
        return new self($this->text, $fontSize, $this->fontPath, $this->angle);
    }

    public function withFontPath($fontPath)
    {
        return new self($this->text, $this->fontSize, $fontPath, $this->angle);
    }

    public function withAngle($angle)
    {
        return new self($this->text, $this->fontSize, $this->fontPath, $angle);
    }

    public function coordinate($position, Size $screenSize, int $spacing = 0, int $lineheight = 0)
    {
        $calc = new Coordinator($this->boundingBox(), $screenSize->expandPixcel((-1) * $spacing, (-1) * $lineheight));

        if ($position instanceof Coordinate) {
            return $calc->fromCoordinate($position);
        }
        return $calc->fromString($position);
    }

    public function boundingBox(): BoundingBox
    {
        $result = imagettfbbox($this->fontSize, $this->angle, $this->fontPath, $this->text);
        return new BoundingBox($result);
    }

    public function fit(Size $size): Text
    {
        $box = $this->boundingBox();
        $fontSize = $this->fontSize;
        
        if ($box->width < $size->width && $box->height < $size->height) {
            $increment = 1;
        } else {
            $increment = -1;
        }

        $candidate = $this;
        $loop = true;
        do {
            $text = $this->withFontSize($fontSize);
            $box = $text->boundingBox();

            $isOver = $box->width < $size->width && $box->height < $size->height;
            if ($isOver && $increment === 1) {
                $candidate = $text;
            } elseif (!$isOver && $increment === -1) {
                $candidate = $text;
            } else {
                $loop = false;
            }
            $fontSize += $increment;
        } while ($loop);

        return $candidate;
    }
}
