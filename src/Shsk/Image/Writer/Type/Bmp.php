<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Bmp extends TypeAbstract
{
    protected $mime = 'image/bmp';

    public function saveAs($filePath)
    {
        $options = $this->getOptions();
        $compressed = $options['compressed'] ?? true;
        return imagebmp($this->getResource(), $filePath, $compressed);
    }

    public function save()
    {
        $this->saveAs($this->getFilePath());
    }

    public function output()
    {
        return (new Buffering(function () {
            $options = $this->getOptions();
            $compressed = $options['compressed'] ?? true;
            return imagebmp($this->getResource(), null, $compressed);
        }))->output();
    }

    public function response()
    {
        $output = $this->output();
        header('Content-Type: image/bmp');
        header('Content-Length: ' . strlen($output));
        echo $output();
    }
}
