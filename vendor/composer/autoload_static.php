<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit38006e4d54fc409dee3747219347ba86
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'cweagans\\Composer\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'cweagans\\Composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/cweagans/composer-patches/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit38006e4d54fc409dee3747219347ba86::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit38006e4d54fc409dee3747219347ba86::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit38006e4d54fc409dee3747219347ba86::$classMap;

        }, null, ClassLoader::class);
    }
}