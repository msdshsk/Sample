<?php

namespace Sample\Image\Writer\Type;

use Sample\Image\Writer\Type;

abstract class GD implements Type
{
    private $resource;
    private $options;
    public function __construct($im, $options = [])
    {
        $this->resource = $im;
        $this->options = $options;
    }
    
    protected function getResource()
    {
        return $this->resource;
    }

    protected function getOptions()
    {
        return $this->options;
    }
}
