<?php

class ClassLoader
{
    private static $dirs;

    public static function loadClass($class)
    {
        foreach (self::directories() as $directory) {
            $fileName = "{$directory}/{$class}.php";

            if (is_file($fileName)) {
                require $fileName;

                return true;
            }
        }
    }

    private static function directories()
    {
        if (empty(self::$dirs)) {
            $base = __DIR__;
            self::$dirs = [
                $base . '/controllers',
                $base . '/models'
            ];
        }

        return self::$dirs;
    }
}

spl_autoload_register(['ClassLoader', 'loadClass']);