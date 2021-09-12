<?php

namespace Sample\Image\Reader;

interface Factory
{
    public function create(): \Sample\Image\Reader\Type;
}
