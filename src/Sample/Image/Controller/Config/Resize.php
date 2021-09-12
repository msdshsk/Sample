<?php

namespace Sample\Image\Controller\Config;

use Sample\Property\Size;
use Sample\Property\ReadOnly;
use Sample\Exception\Exception;

class Resize extends ReadOnly
{
    public function __construct($ary)
    {
        $size = $ary['size'] ?? null;
        $height = $ary['height'] ?? null;
        $width = $ary['width'] ?? null;

        $parsent = $ary['parsent'] ?? null;

        switch (true) {
            case $size instanceof Size:
                break;
            case $height === null && $width === null && $parsent === null:
                throw new Exception("Undefined property. 'height' or 'width'");
            case $parsent !== null:
                $height = null;
                $width = null;
                break;
            default:
                $size = new Size($width, $height);
                break;
        }

        parent::__construct(compact(['size', 'parsent']));
    }

    public function calc(Size $size): Size
    {
        $width = $size->width;
        $height = $size->height;
        $new_width = $this->size->width;
        $new_height = $this->size->height;

        if ($this->parsent !== null) {
            $new_width = $width * $this->parsent;
            $new_height = $height * $this->parsent;

            return new Size($new_width, $new_height);
        }

        if ($new_width === null) {
            $parsent = $new_height / $height;
            $new_width = $width * $parsent;
        } elseif ($new_height === null) {
            $parsent = $new_width / $width;
            $new_height = $height * $parsent;
        }

        return new Size($new_width, $new_height);
    }
}
