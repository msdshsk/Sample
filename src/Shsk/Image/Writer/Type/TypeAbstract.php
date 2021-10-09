<?php

namespace Shsk\Image\Writer\Type;

abstract class TypeAbstract implements TypeInterface
{
    private $resource;
    private $options;
    private $filePath;
    public function __construct($im, $options = [], $filePath = null)
    {
        $this->resource = $im;
        $this->options = $options;
        $this->filePath = $filePath;
    }
    
    protected function getResource()
    {
        return $this->resource;
    }

    protected function getOptions()
    {
        return $this->options;
    }

    protected function getFilePath()
    {
        return $this->filePath;
    }

    public function response()
    {
        $output = $this->output();
        header('Content-Type: ' . $this->mime);
        header('Content-Length: ' . strlen($output));
        echo $output();
    }

    public function encode()
    {
        $output = $this->output();
        $encode = base64_encode($output);

        return sprintf('data:%s;base64,%s', $this->mime, $encode);
    }
}
