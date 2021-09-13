<?php

namespace Shsk\Image\Reader;

interface Factory
{
    public function create(): \Sample\Image\Reader\Type;
}
