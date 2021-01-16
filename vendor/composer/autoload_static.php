<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2e4581077e525a0395b4fcfba0ebec4d
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2e4581077e525a0395b4fcfba0ebec4d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2e4581077e525a0395b4fcfba0ebec4d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2e4581077e525a0395b4fcfba0ebec4d::$classMap;

        }, null, ClassLoader::class);
    }
}