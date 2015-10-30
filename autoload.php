<?php

spl_autoload_register('pddk_autoload');

function pddk_autoload($class_name)
{
    if (false !== strpos($class_name, 'Pddk')) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
        $class_file = strtolower($class_name) . '.php';
        require_once $classes_dir . $class_file;
    }
}
