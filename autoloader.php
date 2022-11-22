<?php
spl_autoload_register(function ($className) {
    $filename = 'inc';
    if (str_contains($className, 'Controller')) $filename .= '/controllers/';
    else if (str_contains($className, 'Model')) $filename .= '/models/';
    else if (str_contains($className, 'Page')) $filename = 'page/';
    else $filename .= '/lib/';
    $filename .= $className . '.php';
    if (file_exists($filename)) require_once $filename;
});
