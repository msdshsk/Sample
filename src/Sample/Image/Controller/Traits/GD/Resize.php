<?php

namespace Sample\Image\Controller\Traits\GD;

use Sample\Image\Controller\Config\Resize as Config;
use Sample\Image\Creator\GD\TrueColor;
use Sample\Property\Size as CommonSize;
use Sample\Image\Controller\GD as Controller;

trait Resize
{
    public function resize(Config $config): Controller
    {
        $height = $this->height();
        $width = $this->width();

        $new_size = $config->calc(new CommonSize($width, $height));

        $copy = (new TrueColor($new_size->width, $new_size->height))->create();
        $im_cpy = $copy->getResource();
        $im_src = $this->getResource();

        imagealphablending($im_cpy, true);
        imagesavealpha($im_cpy, true);
        imagecopyresampled($im_cpy, $im_src, 0, 0, 0, 0, $new_size->width, $new_size->height, $width, $height);

        return $copy;
    }
}
