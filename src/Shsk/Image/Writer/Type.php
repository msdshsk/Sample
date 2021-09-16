<?php

namespace Shsk\Image\Writer;

interface Type
{
    public function saveAs(string $filePath);
    public function save();
}
