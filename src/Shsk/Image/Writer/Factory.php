<?php

namespace Shsk\Image\Writer;

use Shsk\Image\Writer\Type;

interface Factory
{
    public function create(): Type;
}
