<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image\Writer\Factory as WriterFactory;
use Shsk\Image\Reader\Factory as ReaderFactory;
use Shsk\Image\Controller;
use Shsk\Image\Creator\TrueColor;
use Shsk\Image\Color;
use Shsk\Property\Size;

trait IO
{
    public function save($filePath, $options = [])
    {
        $factory = new WriterFactory(null, $this->im, $options, $filePath);
        $writer = $factory->create();

        return $writer->save();
    }

    public function output($type, $options = [])
    {
        $factory = new WriterFactory($type, $this->im, $options);
        $writer = $factory->create();

        return $writer->output();
    }

    public function response($type, $options = [])
    {
        $factory = new WriterFactory($type, $this->im, $options);
        $writer = $factory->create();

        return $writer->response();
    }

    public function encode($type, $options = [])
    {
        $factory = new WriterFactory($type, $this->im, $options);
        $writer = $factory->create();

        return $writer->encode();
    }

    public static function create(Size $size, Color $baseColor = null): Controller
    {
        return (new TrueColor($size->width, $size->height, $baseColor))->create();
    }

    public static function fromImage($filePath): Controller
    {
        return (new ReaderFactory(pathinfo($filePath, PATHINFO_EXTENSION)))->create()->create($filePath);
    }
}