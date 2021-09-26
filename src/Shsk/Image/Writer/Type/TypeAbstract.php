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
}
