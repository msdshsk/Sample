<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Gif extends TypeAbstract
{
    public function saveAs($filePath)
    {
        return imagegif($this->getResource(), $filePath);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }

    public function output()
    {
        return (new Buffering(function () {
            imagegif($this->getResource());
        }))->output();
    }
}
