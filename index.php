<?php

declare(strict_types=1);

namespace App;

spl_autoload_register(function(string $classNamespace){
    $path = str_replace(['\\', "App/"], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

$view = new View();

$view->render(["add", "tree"]);