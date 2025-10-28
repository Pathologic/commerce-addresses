<?php
spl_autoload_register(function ($class) {
    $prefix = 'Pathologic\\Commerce\\Addresses\\';
    if (strpos($class, $prefix) === 0) {
        $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_readable($file)) {
            require $file;
        }
    }
});
