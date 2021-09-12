<?php

namespace Sample\Image\Writer\Type\GD;

use Sample\Image\Writer\Type\GD;

class Gif extends GD
{
    public function saveAs($filePath)
    {
        return imagegif($this->getResource(), $filePath);
    }
}
