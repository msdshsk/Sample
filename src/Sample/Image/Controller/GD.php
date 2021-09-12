<?php

namespace Sample\Image\Controller;

use Sample\Color\RGB as Color;
use Sample\Image\Text;
use Sample\Property\Coordinate;
use Sample\Image\Writer\Factory\GD as WriterFactory;
use Sample\Image\Controller\Traits\GD\Resize;
use Sample\Image\Controller\Traits\GD\Trimming;
use Sample\Image\Controller\Traits\GD\Grid;
use Sample\Image\Controller\Traits\GD\Outline;
use Sample\Property\Size;

class GD implements \Sample\Image\Controller
{
    use Resize;
    use Trimming;
    use Grid;
    use Outline;

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

    public function allocate(Color $color)
    {
        return imagecolorallocatealpha($this->im, $color->red, $color->green, $color->blue, $color->alpha);
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

    public function colorat($x, $y)
    {
        $index = imagecolorat($this->im, $x, $y);
        return Color::createFromIndex($index);
    }

    public function createWriter($filePath): \Sample\Image\Writer\Type
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $factory = new WriterFactory($ext, $this->im);
        return $factory->create();
    }

    public function copy()
    {
        $org = $this->getResource();
        $width = $this->width();
        $height = $this->height();
        $cpy = imagecreatetruecolor($width, $height);
        imagecopy($cpy, $org, 0, 0, 0, 0, $width, $height);

        return new self($cpy, $this->filePath);
    }

    public function destroy()
    {
        imagedestroy($this->im);
    }

    public function getResource()
    {
        return $this->im;
    }
}
