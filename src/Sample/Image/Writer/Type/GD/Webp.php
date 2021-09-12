<?php

namespace Sample\Image\Writer\Type\GD;

use Sample\Image\Writer\Type\GD;

class Webp extends GD
{
    public function saveAs($filePath)
    {
        $options = $this->getOptions();

        $quality = $options['quality'] ?? -1;

        return imagewebp($this->getResource(), $filePath, $quality);
    }
}
