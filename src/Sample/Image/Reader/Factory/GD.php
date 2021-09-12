<?php

namespace Sample\Image\Reader\Factory;

use Sample\Image\Reader\Type\GD\Gif;
use Sample\Image\Reader\Type\GD\Jpg;
use Sample\Image\Reader\Type\GD\Png;
use Sample\Image\Reader\Type\GD\Webp;

class GD implements \Sample\Image\Reader\Factory
{
    const TYPES = [
        'jpg' => Jpg::class,
        'jpeg' => Jpg::class,
        'gif' => Gif::class,
        'webp' => Webp::class,
        'png' => Png::class,
    ];

    private $extension;

    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    public function create(): \Sample\Image\Reader\Type
    {
        $ext = strtolower($this->extension);
        if (!isset(self::TYPES[$ext])) {
            throw new \Sample\Exception\Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class();
    }
}
