<?php

namespace Shsk\Image;

use Shsk\Image\Color;
use Shsk\Image\Text;
use Shsk\Property\Coordinate;
use Shsk\Image\Writer\Factory as WriterFactory;
use Shsk\Image\Reader\Factory as ReaderFactory;
use Shsk\Image\Writer\Type\TypeInterface;
use Shsk\Property\Size;
use Shsk\Image\Controller\Config\Resize as ResizeConfig;
use Shsk\Image\Controller\Config\Trimming as TrimConfig;
use Shsk\Image\Creator\TrueColor;
use Shsk\Image\Controller\Traits;

class Controller
{
    use Traits\Colors;
    use Traits\Draw;
    use Traits\Immutable;

    private $im;
    private $filePath;

    public function __construct($im, $filePath = null)
    {
        $this->im = $im;
        $this->filePath = $filePath;
    }

    public function width(): int
    {
        return imagesx($this->im);
    }

    public function height(): int
    {
        return imagesy($this->im);
    }

    public function size(): Size
    {
        return new Size($this->width(), $this->height());
    }

    public function path()
    {
        return $this->filePath;
    }

    public function fill($x, $y, Color $color)
    {
        $index = $this->allocate($color);
        return imagefill($this->im, $x, $y, $index);
    }

    public function filter($filter)
    {
        return imagefilter($this->im, $filter);
    }

    public function writeText(Text $text, Coordinate $coord, Color $color)
    {
        $index = $this->allocate($color);
        return imagettftext($this->im, $text->fontSize, $text->angle, $coord->x, $coord->y, $index, $text->fontPath, $text->text);
    }

    public function createWriter($filePath, $options = []): TypeInterface
    {
        $factory = new WriterFactory(null, $this->im, $options, $filePath);
        return $factory->create();
    }

    public function destroy()
    {
        imagedestroy($this->im);
    }

    public function getResource()
    {
        return $this->im;
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
