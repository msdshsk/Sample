<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Webp extends TypeAbstract
{
    public function saveAs($filePath)
    {
        $options = $this->getOptions();

        $quality = $options['quality'] ?? -1;

        return imagewebp($this->getResource(), $filePath, $quality);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }

    public function output()
    {
        return (new Buffering(function () {
            $options = $this->getOptions();
            $quality = $options['quality'] ?? -1;
            imagewebp($this->getResource(), null, $quality);
        }))->output();
    }
}
