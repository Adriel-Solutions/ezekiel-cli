<?php

    function autoloader($classname) {
        $root = __DIR__ . DIRECTORY_SEPARATOR;

        $path = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, $classname)) . '.php';
        $filepath = join(DIRECTORY_SEPARATOR, [ $root , $path ]);

        // Prevent exceptions
        if(!file_exists($filepath)) return;

        require $filepath;
    }
    
    spl_autoload_register('autoloader');
