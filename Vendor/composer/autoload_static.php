<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite4ccab0c7f4be173eaf1eea1f85efce1
{
    public static $files = array (
        '93e518b9aaa80f1db952e682be199fbc' => __DIR__ . '/../..' . '/db_cfg.php',
    );

    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Engine\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Engine\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Engine',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite4ccab0c7f4be173eaf1eea1f85efce1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite4ccab0c7f4be173eaf1eea1f85efce1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
