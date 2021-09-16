<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbb9600e558571594055013fd7fca88a8
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'Yasumi\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Yasumi\\' => 
        array (
            0 => __DIR__ . '/..' . '/azuyalabs/yasumi/src/Yasumi',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbb9600e558571594055013fd7fca88a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbb9600e558571594055013fd7fca88a8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbb9600e558571594055013fd7fca88a8::$classMap;

        }, null, ClassLoader::class);
    }
}