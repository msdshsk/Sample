<?php

namespace Shsk\Image\Writer\Type\GD;

use Shsk\Image\Writer\Type\GD;

class Gif extends GD
{
    public function saveAs($filePath)
    {
        return imagegif($this->getResource(), $filePath);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }
}
