<?php

namespace Shsk\FileSystem\Directory;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class SearchDirectoryIterator extends RecursiveIteratorIterator
{
    private $keyword = null;
    public function __construct($dir, $keyword = null)
    {
        $this->keyword = $keyword;
        parent::__construct(new RecursiveDirectoryIterator($dir));
    }

    public function next()
    {
        parent::next();
        if (!parent::valid()) {
            return;
        }
        while (false === $this->validateKeyword(parent::current())) {
            parent::next();
            if (!parent::valid()) {
                break;
            }
        }
    }

    public function rewind()
    {
        parent::rewind();
        if (!parent::valid()) {
            return;
        }

        if (!$this->validateKeyword(parent::current())) {
            $this->next();
        }
    }

    private function validateKeyword(\SplFileInfo $file): bool
    {
        if ($file->getFilename() !== '.') {
            return false;
        }

        if (!$file->isDir()) {
            return false;
        }

        if ($this->keyword === null) {
            return true;
        }

        if (true === (bool) preg_match($this->keyword, $file->getPath(), $match)) {
            return true;
        }

        return false;
    }
}
