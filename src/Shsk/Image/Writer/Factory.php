<?php

namespace Shsk\Image\Writer;

use Shsk\Image\Writer\Type\TypeInterface;
use Shsk\Image\Writer\Type\Gif;
use Shsk\Image\Writer\Type\Jpg;
use Shsk\Image\Writer\Type\Png;
use Shsk\Image\Writer\Type\Webp;
use Shsk\Image\Writer\Type\Svg;
use Shsk\Exception\Exception;

class Factory implements FactoryInterface
{
    const TYPES = [
        'jpg' => Jpg::class,
        'jpeg' => Jpg::class,
        'gif' => Gif::class,
        'webp' => Webp::class,
        'png' => Png::class,
        'svg' => Svg::class,
    ];
    private $extension;
    private $resource;
    private $options;
    private $filePath;
    
    public function __construct($extension, $resource, $options = [], $filePath = null)
    {
        if ($extension === null && $filePath !== null) {
            $this->extension = pathinfo($filePath, PATHINFO_EXTENSION);
        } else {
            $this->extension = $extension;
        }
        $this->resource = $resource;
        $this->options = $options;
        $this->filePath = $filePath;
    }

    public function create(): TypeInterface
    {
        $ext = strtolower($this->extension);

        if (!isset(self::TYPES[$ext])) {
            throw new Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class($this->resource, $this->options, $this->filePath);
    }
}
