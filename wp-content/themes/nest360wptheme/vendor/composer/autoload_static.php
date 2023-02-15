<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc5daa25e864219641551643e1d48b5e5
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'NEST360WPTheme\\wp_trigger\\' => 26,
            'NEST360WPTheme\\inc\\' => 19,
            'NEST360WPTheme\\endpoints\\' => 25,
            'NEST360WPTheme\\custom_posts\\' => 28,
        ),
        'D' => 
        array (
            'DrewM\\Drip\\' => 11,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'NEST360WPTheme\\wp_trigger\\' => 
        array (
            0 => __DIR__ . '/../..' . '/wp_trigger',
        ),
        'NEST360WPTheme\\inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
        'NEST360WPTheme\\endpoints\\' => 
        array (
            0 => __DIR__ . '/../..' . '/endpoints',
        ),
        'NEST360WPTheme\\custom_posts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/custom_posts',
        ),
        'DrewM\\Drip\\' => 
        array (
            0 => __DIR__ . '/..' . '/drewm/drip/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc5daa25e864219641551643e1d48b5e5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc5daa25e864219641551643e1d48b5e5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc5daa25e864219641551643e1d48b5e5::$classMap;

        }, null, ClassLoader::class);
    }
}
