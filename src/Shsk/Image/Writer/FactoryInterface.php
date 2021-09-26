<?php

namespace Shsk\Image\Writer;

use Shsk\Image\Writer\Type\TypeInterface;

interface FactoryInterface
{
    public function create(): TypeInterface;
}
