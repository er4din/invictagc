<?php

/**
 * Use to autoload needed classes without Composer.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {
    $map = [
        'Abraham\\TwitterOAuth\\'  => __DIR__ . '/',
        'Composer\\CaBundle\\'     => __DIR__ . '/',
    ];

    foreach ($map as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) continue;

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
});

