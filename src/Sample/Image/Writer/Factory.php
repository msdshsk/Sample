<?php

namespace Sample\Image\Writer;

interface Factory
{
    public function create(): \Sample\Image\Writer\Type;
}
