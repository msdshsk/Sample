<?php

namespace Shsk\Image\Writer;

interface Factory
{
    public function create(): \Sample\Image\Writer\Type;
}
