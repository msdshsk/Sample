<?php

namespace Shsk\Image\Writer\Type;

interface TypeInterface
{
    public function saveAs(string $filePath);
    public function save();
    public function output();
}
