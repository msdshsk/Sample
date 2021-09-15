<?php

namespace Shsk;

class Autoload
{
    const ROOT = __DIR__;

    private static $registed = false;

    public static function register()
    {
        if (self::$registed === false) {
            self::$registed = spl_autoload_register([__CLASS__, 'include']);
            return self::$registed;
        }
        return self::$registed;
    }

    public static function include($class)
    {
        if (strpos($class, 'Shsk\\') === 0) {
            $class = substr($class, 5);
            require_once self::ROOT . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        }
    }

    public static function useFunctions()
    {
        require_once self::ROOT . DIRECTORY_SEPARATOR . 'functions.php';
    }
}
