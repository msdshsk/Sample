<?php

namespace Sample\Image\Reader;

use Sample\Image\Controller;

interface Type
{
    public function create($filePath): Controller;
}
