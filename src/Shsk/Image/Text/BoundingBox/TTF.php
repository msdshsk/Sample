<?php

namespace Shsk\Image\Text\BoundingBox;

class TTF extends \Sample\Image\Text\BoundingBox
{
    protected function createBox(int $size, int $angle, string $fontPath, string $text): array
    {
        return imagettfbbox($size, $angle, $fontPath, $text);
    }
}
