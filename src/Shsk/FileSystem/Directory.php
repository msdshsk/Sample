<?php

namespace Shsk\FileSystem;

use Shsk\Exception\FunctionException;
use Shsk\FileSystem\Directory\SearchDirectoryIterator;
use Shsk\FileSystem\Directory\SearchFileIterator;
use Shsk\Exception\Exception;

class Directory
{
    const DS = DIRECTORY_SEPARATOR;
    private $rootDir;

    public function __construct($rootDir)
    {
        $rootDir = self::getAbsolutePath($rootDir, true);

        $this->rootDir = $rootDir;
    }

    public function exists()
    {
        return is_dir($this->rootDir);
    }

    public function path($path = null)
    {
        if ($path === null) {
            return $this->rootDir;
        }
        $path = self::cleanPath($path, false);
        if ($path[0] === self::DS) {
            return $this->rootDir . $path;
        }

        return $this->rootDir . self::DS . $path;
    }

    public function make()
    {
        return self::makeDirectory($this->rootDir);
    }

    public function searchFiles($keyword = null, $returnIterator = true)
    {
        $iterator = new SearchFileIterator($this->rootDir, $keyword);
        if ($returnIterator === true) {
            return $iterator;
        }

        $results = [];
        foreach ($iterator as $file) {
            $results[] = $file->getPathname();
        }
        return $results;
    }

    public function searchDirectory($keyword = null, $returnIterator = true)
    {
        $iterator = new SearchDirectoryIterator($this->rootDir, $keyword);
        if ($returnIterator === true) {
            return $iterator;
        }

        $results = [];
        foreach ($iterator as $file) {
            $results[] = $file->getPathname();
        }
        return $results;
    }

    public static function makeDirectory($path, $parmissions = 0777)
    {
        $path = self::cleanPath($path);

        if (is_dir($path)) {
            return;
        }

        $throw = null;
        try {
            FunctionException::start();
            mkdir($path, $parmissions, true);
        } catch (FunctionException $e) {
            $throw = $e;
        } finally {
            FunctionException::end();
        }
        if ($throw !== null) {
            throw $throw;
        }
    }

    public static function cleanPath($path, $absolute = false)
    {
        $path = str_replace(['\\', '/'], self::DS, $path);
        if (substr($path, strlen($path), 1) === self::DS) {
            $path = substr($path, 0, strlen($path) - 1);
        }

        if (strpos($path, ':') === false && $path[0] !== self::DS && $absolute === true) {
            $path = getcwd() . self::DS . $path;
        }

        return $path;
    }

    public static function getAbsolutePath($path, $absolute = false)
    {
        $path = self::cleanPath($path, $absolute);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $i => $part) {
            if ('.' === $part) {
                continue;
            }
            if ('..' === $part) {
                if ($i === 0) {
                    throw new Exception("can't prosess first directory because '..' is upper.");
                }
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $absolutes);
    }
}
