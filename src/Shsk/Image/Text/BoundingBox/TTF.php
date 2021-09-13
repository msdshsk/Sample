<?php

namespace Shsk\Image\Text\BoundingBox;

use Shsk\Image\Text\BoundingBox as BoundingBoxAbstract;

class TTF extends BoundingBoxAbstract
{
    protected function createBox(int $size, int $angle, string $fontPath, string $text): array
    {
        return imagettfbbox($size, $angle, $fontPath, $text);
    }
}
