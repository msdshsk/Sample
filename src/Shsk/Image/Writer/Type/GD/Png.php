<?php

namespace Shsk\Image\Writer\Type\GD;

use \Sample\Image\Writer\Type\GD;

class Png extends GD
{
    public function saveAs($filePath)
    {
        $options = $this->getOptions();
        $quality = $options['quality'] ?? -1;
        $filters = $options['filters'] ?? -1;

        return imagepng($this->getResource(), $filePath, $quality, $filters);
    }
}
