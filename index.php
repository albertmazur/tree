<?php

declare(strict_types=1);

spl_autoload_register(function(string $classNamespace){
    $path = str_replace(['\\', "App/"], ['/', ''], $classNamespace);
    $path = "src/$path.php";
    require_once($path);
});

use App\Controller\CategoryController;
use App\Exception\ConfigurationException;

require_once ('config/database.php');
require_once ('config/env.php');
loadEnv(__DIR__ . '/.env');
$conf = require_once ("config/config.php");

try {
    $dsn = "mysql:host={$conf['db']['host']}";
    $pdo = new PDO($dsn, $conf['db']['user'], $conf['db']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    createDatabaseAndTables($pdo, $conf['db']['database'], $conf['db']['table']);

} catch (PDOException|ConfigurationException $e) {
    (new \App\View())->render(["error"], ["error" => "Błąd konfiguracj bazy danych".$e->getMessage()]);
}

try {
    $controller = new CategoryController($conf, new \App\Request($_GET, $_POST));
    $controller->run();

} catch (ConfigurationException $e) {
    (new \App\View())->render(["error"], ["error" => "Błąd konfiguracji"]);
}

