<?php

namespace Shsk\Image;

use Shsk\Property\Size;
use Shsk\Image\Writer\Type;

interface Controller
{
    public function width(): int;
    public function height(): int;
    public function size(): Size;
    public function createWriter($filePath): Type;
    public function getResource();
}
