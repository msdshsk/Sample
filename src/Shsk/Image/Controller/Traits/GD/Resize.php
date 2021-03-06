<?php

namespace Shsk\Image\Controller\Traits\GD;

use Shsk\Image\Controller\Config\Resize as Config;
use Shsk\Image\Creator\GD\TrueColor;
use Shsk\Property\Size as CommonSize;
use Shsk\Image\Controller\GD as Controller;
use Shsk\Color\RGB as Color;

trait Resize
{
    public function resize(Config|array $config): Controller
    {
        $height = $this->height();
        $width = $this->width();

        if (is_array($config)) {
            $config = new Config($config);
        }

        $new_size = $config->calc(new CommonSize($width, $height));

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

        return $copy;
    }
}
