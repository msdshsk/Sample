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

    public function exists(): bool
    {
        return is_dir($this->rootDir);
    }

    public function path($path = null): string
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

    public function make(): bool
    {
        return self::makeDirectory($this->rootDir);
    }

    public function remove($recursive = false)
    {
        $throw = null;
        try {
            FunctionException::start();
            if ($recursive === true) {
                $files = $this->searchFiles();
                foreach ($files as $filePath) {
                    unlink($filePath);
                }
                $dirs = $this->searchDirectories();
                $dirs = self::sortDepth($dirs, false);
    
                foreach ($dirs as $dir) {
                    rmdir($dir);
                }
            } else {
                rmdir($this->rootDir);
            }
        } catch (FunctionException $e) {
            $throw = $e;
        } finally {
            FunctionException::end();
        }
        if ($throw !== null) {
            throw $throw;
        }

        return true;
    }

    public function getSearchFileIterator($keyword = null): SearchFileIterator
    {
        return new SearchFileIterator($this->rootDir, $keyword);
    }

    public function getSearchDirectoryIterator($keyword = null): SearchDirectoryIterator
    {
        return new SearchDirectoryIterator($this->rootDir, $keyword);
    }

    public function searchFiles($keyword = null): array
    {
        $iterator = $this->getSearchFileIterator($keyword);

        $results = [];
        foreach ($iterator as $file) {
            $results[] = $file->getPathname();
        }
        return $results;
    }

    public function searchDirectories($keyword = null): array
    {
        $iterator = $this->getSearchDirectoryIterator($keyword);

        $results = [];
        foreach ($iterator as $file) {
            $results[] = $file->getPath();
        }
        return $results;
    }

    public static function makeDirectory($path, $parmissions = 0777): bool
    {
        $path = self::cleanPath($path);

        if (is_dir($path)) {
            return false;
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

        return true;
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

    public static function sortDepth($dirs, $asc = true)
    {
        usort($dirs, function ($a, $b) use ($asc) {
            $count_a = count(explode(DIRECTORY_SEPARATOR, $a));
            $count_b = count(explode(DIRECTORY_SEPARATOR, $b));

            if ($a === $b) {
                return 0;
            }

            $x = $asc ? $a : $b;
            $y = $asc ? $b : $a;

            return ($x < $y) ? -1 : 1;
        });

        return $dirs;
    }
}
