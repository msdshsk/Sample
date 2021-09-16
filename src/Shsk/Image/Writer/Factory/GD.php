<?php

namespace Shsk\Image\Writer\Factory;

use Shsk\Image\Writer\Type\GD\Gif;
use Shsk\Image\Writer\Type\GD\Jpg;
use Shsk\Image\Writer\Type\GD\Png;
use Shsk\Image\Writer\Type\GD\Webp;
use Shsk\Image\Writer\Type;
use Shsk\Image\Writer\Factory;
use Shsk\Exception\Exception;

class GD implements Factory
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

    public function create(): Type
    {
        $ext = strtolower($this->extension);

        if (!isset(self::TYPES[$ext])) {
            throw new Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class($this->resource, $this->options, $this->filePath);
    }
}
