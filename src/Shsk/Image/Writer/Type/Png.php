<?php

namespace Shsk\Image\Writer\Type;

use Shsk\Utility\Buffering;

class Png extends TypeAbstract
{
    protected $mime = 'image/png';

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

    public function output()
    {
        return (new Buffering(function () {
            $options = $this->getOptions();
            $quality = $options['quality'] ?? -1;
            $filters = $options['filters'] ?? -1;
            imagepng($this->getResource(), null, $quality, $filters);
        }))->output();
    }

    public function response()
    {
        $output = $this->output();
        header('Content-Type: image/png');
        header('Content-Length: ' . strlen($output));
        echo $output();
    }
}
