<?php
spl_autoload_register('autoload');

function autoload($class_name)
{
    $directories = [
        '/src/',
        '/Twig/lib/Twig/',
    ];

    foreach ($directories as $directory) {
        $prefix = 'Twig_';
        $len = strlen($prefix);
        if (strncmp($prefix, $class_name, $len) == 0) {
            $class_name = substr($class_name, $len);
        }

        $filename = $_SERVER['DOCUMENT_ROOT'].$directory.$class_name;
        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename) . '.php';

        if (file_exists($filename)) {
            require_once($filename);
        }
    }

    return;
}
