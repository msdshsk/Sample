<?php

namespace Shsk\Image;

use Shsk\Image\Controller\Traits;
use Shsk\Property\Size;

class Controller
{
    use Traits\Draw;
    use Traits\Immutable;
    use Traits\Text;
    use Traits\IO;

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

    public function allocate(Color $color)
    {
        return imagecolorallocatealpha($this->im, $color->red, $color->green, $color->blue, $color->alpha);
    }

    public function transparent(Color $color = null): int
    {
        return imagecolortransparent($this->im, $color ? $color->toIndex() : null);
    }

    public function colorat($x, $y)
    {
        $index = imagecolorat($this->im, $x, $y);
        return Color::createFromIndex($index);
    }

    public function text(Text $text)
    {
        return new Controller\Text($this, $text);
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
