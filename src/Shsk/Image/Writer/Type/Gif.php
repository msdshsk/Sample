<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Gif extends TypeAbstract
{
    protected $mime = 'image/gif';

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

    public function response()
    {
        $output = $this->output();
        header('Content-Type: image/gif');
        header('Content-Length: ' . strlen($output));
        echo $output();
    }
}
