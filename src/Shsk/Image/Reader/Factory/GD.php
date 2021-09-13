<?php

namespace Shsk\Image\Reader\Factory;

use Shsk\Image\Reader\Type\GD\Gif;
use Shsk\Image\Reader\Type\GD\Jpg;
use Shsk\Image\Reader\Type\GD\Png;
use Shsk\Image\Reader\Type\GD\Webp;
use Shsk\Image\Reader\Factory;
use Shsk\Image\Reader\Type;
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

    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    public function create(): Type
    {
        $ext = strtolower($this->extension);
        if (!isset(self::TYPES[$ext])) {
            throw new Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class();
    }
}
