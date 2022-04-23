<?php

declare(strict_types=1);

spl_autoload_register(function(string $classNamespace){
    $path = str_replace(['\\', "App/"], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

use App\Controller\CategoryController;
use App\Exception\ConfigurationException;

$conf = require_once ("config/config.php");

try {
    $controller = new CategoryController($conf );
    if(isset($_GET['action'])){
        $controller->run( ['action' => $_GET['action']]);
    }
    else{
        $controller->run();
    }
} catch (ConfigurationException $e) {
    echo "<h1>Błąd configuracji</h1>";
}
