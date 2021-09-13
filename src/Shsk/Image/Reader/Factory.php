<?php

namespace Shsk\Image\Reader;

use Shsk\Image\Reader\Type;

interface Factory
{
    public function create(): Type;
}
