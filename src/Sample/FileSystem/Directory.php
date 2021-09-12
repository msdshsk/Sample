<?php

namespace Sample\FileSystem;

use Sample\Exception\FunctionException;
use Sample\FileSystem\Directory\SearchDirectoryIterator;
use Sample\FileSystem\Directory\SearchFileIterator;

class Directory
{
    const DS = DIRECTORY_SEPARATOR;
    private $rootDir;

    public function __construct($rootDir)
    {
        $rootDir = self::cleanPath($rootDir);

        if (strpos($rootDir, ':') === false && $rootDir[0] !== '/') {
            $rootDir = getcwd() . self::DS . $rootDir;
        }

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

    public static function cleanPath($path)
    {
        $path = str_replace(['\\', '/'], self::DS, $path);
        if (substr($path, strlen($path), 1) === self::DS) {
            $path = substr($path, 0, strlen($path) - 1);
        }

        if (strpos($path, ':') === false && $path[0] !== self::DS) {
            $path = getcwd() . self::DS . $path;
        }

        return $path;
    }
}
