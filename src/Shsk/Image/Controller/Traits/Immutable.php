<?php

namespace Shsk\Image\Controller\Traits;

use Shsk\Image\Controller\Config\Resize as ResizeConfig;
use Shsk\Image\Controller\Config\Trimming;
use Shsk\Image\Controller;
use Shsk\Image\Creator\TrueColor;
use Shsk\Image\Color;
use Shsk\Property\Size;
use Shsk\Property\Coordinate;

trait Immutable
{
    public function copy(): Controller
    {
        $org = $this->getResource();
        $width = $this->width();
        $height = $this->height();
        $cpy = imagecreatetruecolor($width, $height);
        imagecopy($cpy, $org, 0, 0, 0, 0, $width, $height);

        return new self($cpy, $this->filePath);
    }

    public function resize(ResizeConfig|array $config, $destroy = false): Controller
    {
        $height = $this->height();
        $width = $this->width();

        if (is_array($config)) {
            $config = new ResizeConfig($config);
        }

        $new_size = $config->calc(new Size($width, $height));

        $copy = (new TrueColor($new_size->width, $new_size->height))->create();

        $trans_index = $this->transparent();
        if ($trans_index !== -1) {
            $trans_color = Color::createFromIndex($trans_index);
            $copy->transparent($trans_color);
            $copy->fill(0, 0, $trans_color);
        }

        $im_cpy = $copy->getResource();
        $im_src = $this->getResource();

        imagealphablending($im_cpy, true);
        imagesavealpha($im_cpy, true);
        imagecopyresampled($im_cpy, $im_src, 0, 0, 0, 0, $new_size->width, $new_size->height, $width, $height);

        if ($destroy === true) {
            $this->destroy();
        }

        return $copy;
    }

    public function trimming(Size $size, Coordinate $coord, $destroy = false): Controller
    {
        $copy = (new TrueColor($size->width, $size->height))->create();
        $im_cpy = $copy->getResource();
        $im_src = $this->getResource();

        imagealphablending($im_cpy, true);
        imagesavealpha($im_cpy, true);
        imagecopyresampled(
            $im_cpy,
            $im_src,
            0,
            0,
            $coord->x,
            $coord->y,
            $size->width,
            $size->height,
            $size->width,
            $size->height
        );

        if ($destroy === true) {
            $this->destroy();
        }

        return $copy;
    }
}
