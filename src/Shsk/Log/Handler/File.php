<?php

namespace Shsk\Log\Handler;

use Shsk\FileSystem\Directory;

class File implements HandlerInterface
{
    private $dir;

    public function __construct($logDir)
    {
        $this->dir = new Directory($logDir);
        if (!$this->dir->exists()) {
            $this->dir->make(true);
        }
    }

    public function write($level, $message, array $context = [])
    {
    }
}
