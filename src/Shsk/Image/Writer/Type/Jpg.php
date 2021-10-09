<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Jpg extends TypeAbstract
{
    protected $mime = 'image/jpeg';

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

    public function output()
    {
        return (new Buffering(function () {
            $options = $this->getOptions();
            $quality = $options['quality'] ?? -1;
            imagejpeg($this->getResource(), null, $quality);
        }))->output();
    }

    public function response()
    {
        $output = $this->output();
        header('Content-Type: image/jpeg');
        header('Content-Length: ' . strlen($output));
        echo $output();
    }
}
