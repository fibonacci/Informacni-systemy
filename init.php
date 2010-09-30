<?php
function __autoload($class_name) {
    $filename = 'classes/' . $class_name . '.php';
    if(file_exists($filename)){
        require_once $filename;
    }
}

require_once 'config.php';
dibi::connect($dtbConfig);

setup::createTables();
?>
