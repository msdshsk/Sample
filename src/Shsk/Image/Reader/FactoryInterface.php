<?php

namespace Shsk\Image\Reader;

use Shsk\Image\Reader\Type\TypeInterface;

interface FactoryInterface
{
    public function create(): TypeInterface;
}
