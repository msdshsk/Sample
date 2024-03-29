<?php

namespace Shsk\FileSystem\Directory;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Shsk\FileSystem\Directory;
use Shsk\FileSystem\File;

class SearchFileIterator extends RecursiveIteratorIterator
{
    private $keyword = "";
    private $deep;
    private $currentDepth;
    private $currentDir;
    public function __construct(string $dir, $keyword = null, bool $deep = true)
    {
        $this->keyword = $keyword;
        $this->deep = $deep;
        $this->currentDir = Directory::cleanPath($dir, false);

        if ($deep === false) {
            $this->currentDepth = substr_count($this->currentDir, DIRECTORY_SEPARATOR);
        }
        
        parent::__construct(new RecursiveDirectoryIterator($dir));
    }

    public function current()
    {
        $spl = parent::current();
        $spl->setInfoClass(FIle::class);

        return $spl->getFileInfo();
    }

    public function next(): void
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

    public function rewind(): void
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
        if ($file->getFilename() === '.' || $file->getFilename() === '..') {
            return false;
        }
        
        $valid = true;
        if ($this->deep === false) {
            $valid = substr_count($file->getPath(), DIRECTORY_SEPARATOR) - 1 === $this->currentDepth;
        }

        if ($this->keyword === null) {
            return $valid;
        }

        if ($valid === true) {
            if (is_callable($this->keyword)) {
                return call_user_func($this->keyword, $file);
            }

            if (true === (bool) preg_match($this->keyword, $file->getFilename(), $match)) {
                return true;
            }
        }

        return false;
    }
}
