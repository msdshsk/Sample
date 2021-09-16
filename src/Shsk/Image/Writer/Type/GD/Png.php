<?php

namespace Shsk\Image\Writer\Type\GD;

use Shsk\Image\Writer\Type\GD;

class Png extends GD
{
    public function saveAs($filePath)
    {
        $options = $this->getOptions();
        $quality = $options['quality'] ?? -1;
        $filters = $options['filters'] ?? -1;

        return imagepng($this->getResource(), $filePath, $quality, $filters);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }
}
