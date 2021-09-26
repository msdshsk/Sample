<?php

namespace Shsk\Image\Reader;

use Shsk\Image\Reader\Type\Gif;
use Shsk\Image\Reader\Type\Jpg;
use Shsk\Image\Reader\Type\Png;
use Shsk\Image\Reader\Type\Webp;
use Shsk\Image\Reader\Type\TypeInterface;
use Shsk\Exception\Exception;

class Factory implements FactoryInterface
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

    public function create(): TypeInterface
    {
        $ext = strtolower($this->extension);
        if (!isset(self::TYPES[$ext])) {
            throw new Exception("unread extension. {$ext}");
        }

        $class = self::TYPES[$ext];

        return new $class();
    }
}
