<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderIniteb1373fc9032e21645bdb48e2d5d15a1
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderIniteb1373fc9032e21645bdb48e2d5d15a1', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderIniteb1373fc9032e21645bdb48e2d5d15a1', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticIniteb1373fc9032e21645bdb48e2d5d15a1::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}