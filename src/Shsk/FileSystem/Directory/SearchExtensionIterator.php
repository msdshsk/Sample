<?php

namespace Shsk\FileSystem\Directory;

class SearchExtensionIterator extends SearchFileIterator
{
    public function __construct($dir, array $extensions)
    {
        $code = implode('|', $extensions);
        parent::__construct($dir, "/\\.(?:{$code})$/i");
    }
}