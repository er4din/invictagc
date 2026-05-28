<?php

spl_autoload_register(function ($class) {
    $map = [
        'Google\\'                => __DIR__ . '/src/Google/',
        //'Monolog\\'               => __DIR__ . '/src/Monolog/',
        'GuzzleHttp\\'            => __DIR__ . '/src/GuzzleHttp/',
        'GuzzleHttp\\Psr7\\'      => __DIR__ . '/src/GuzzleHttp/Psr7/',
        'Psr\\Log\\'              => __DIR__ . '/src/Psr/Log/',
        'Psr\\Http\\Message\\'    => __DIR__ . '/src/Psr/Http/Message/',
        'Psr\\Http\\Client\\'     => __DIR__ . '/src/Psr/Http/Client/',
        'Psr\\Cache\\'            => __DIR__ . '/src/Psr/Cache/',
    ];

    foreach ($map as $prefix => $base_dir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) continue;
        $file = $base_dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) require_once $file;
    }
});

