<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image\Creator\TrueColor;
use Shsk\Image\Color;
use Shsk\Property\Size;
use Shsk\Property\Coordinate;
use Shsk\Image\Controller;

trait Immutable
{
    public function copy(Size $size = null): Controller
    {
        if ($size instanceof Size) {
            $width = $size->width;
            $height = $size->height;
        } else {
            $width = $this->width();
            $height = $this->height();
        }

        $copy = (new TrueColor($width, $height))->create();

        $trans_index = $this->transparent();
        if ($trans_index !== -1) {
            $trans_color = Color::createFromIndex($trans_index);
            $copy->transparent($trans_color);
            $copy->fill(0, 0, $trans_color);
        }

        imagecopy($copy->getResource(), $this->getResource(), 0, 0, 0, 0, $width, $height);

        return new self($copy->getResource(), $this->path());
    }

    public function resample(Size $copySize, Coordinate $copyCoord, Size $orgSize, Coordinate $orgCoord)
    {
        $copy = $this->copy($copySize);

        imagealphablending($copy->getResource(), true);
        imagesavealpha($copy->getResource(), true);
        imagecopyresampled(
            $copy->getResource(),
            $this->getResource(),
            $copyCoord->x,
            $copyCoord->y,
            $orgCoord->x,
            $orgCoord->y,
            $copySize->width,
            $copySize->height,
            $orgSize->width,
            $orgSize->height
        );

        return $copy;
    }

    public function resize(): Controller\Resize
    {
        return new Controller\Resize($this);
    }

    public function trimming(): Controller\Trimming
    {
        return new Controller\Trimming($this);
    }
}
