<?php

namespace Shsk;

class Autoload
{
    const ROOT = __DIR__;
    public static function register()
    {
        return spl_autoload_register([__CLASS__, 'include']);
    }

    public static function include($class)
    {
        if (strpos($class, 'Shsk\\') === 0) {
            $class = substr($class, 7);
            require_once self::ROOT . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        }
    }

    public static function useFunctions()
    {
        require_once self::ROOT . DIRECTORY_SEPARATOR . 'functions.php';
    }
}
