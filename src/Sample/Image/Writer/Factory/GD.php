<?php

namespace Sample\Image\Writer\Factory;

use Sample\Image\Writer\Type\GD\Gif;
use Sample\Image\Writer\Type\GD\Jpg;
use Sample\Image\Writer\Type\GD\Png;
use Sample\Image\Writer\Type\GD\Webp;
use Sample\Exception\Exception;

class GD implements \Sample\Image\Writer\Factory
{
    const TYPES = [
        'jpg' => Jpg::class,
        'jpeg' => Jpg::class,
        'gif' => Gif::class,
        'webp' => Webp::class,
        'png' => Png::class,
    ];
    private $extension;
    private $resource;
    private $options;
    
    public function __construct($extension, $resource, $options = [])
    {
        $this->extension = $extension;
        $this->resource = $resource;
        $this->options = $options;
    }

    public function create(): \Sample\Image\Writer\Type
    {
        $ext = strtolower($this->extension);

        if (!isset(self::TYPES[$ext])) {
            throw new Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class($this->resource, $this->options);
    }
}
