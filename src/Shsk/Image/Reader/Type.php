<?php

namespace Shsk\Image\Reader;

use Shsk\Image\Controller;

interface Type
{
    public function create($filePath): Controller;
}
