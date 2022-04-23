<?php
spl_autoload_register(function(string $classNamespace){
    $path = str_replace(['\\', "App/"], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

$requestPaylod = file_get_contents("php://input");
$a = json_decode($requestPaylod);
$controller = new \App\Controller\CategoryController(require_once ("config/config.php"));
$children = $controller->getChildren((int)($a->id));
echo json_encode($children);
