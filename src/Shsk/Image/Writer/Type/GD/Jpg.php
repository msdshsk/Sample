<?php

namespace Shsk\Image\Writer\Type\GD;

use Shsk\Image\Writer\Type\GD;

class Jpg extends GD
{
    public function saveAs($filePath)
    {
        $options = $this->getOptions();

        $quality = $options['quality'] ?? -1;

        return imagejpeg($this->getResource(), $filePath, $quality);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }
}
