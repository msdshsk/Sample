<?php

namespace Shsk\Image\Reader\Type;

use Shsk\Image\Controller;

interface TypeInterface
{
    public function create($filePath): Controller;
}
