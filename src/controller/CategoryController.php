<?php

namespace App\Controller;


use App\Database;
use App\Exception\ConfigurationException;
use App\View;

class CategoryController
{
    private Database $database;
    private View $view;

    public function getChildren (int $id):array {
        return $this->database->downloadChildrenTree($id);
    }

    public function __construct(array $configuration ){
        if(empty($configuration['db'])) throw new ConfigurationException("Configuration error");

        $this->database = new Database($configuration['db']);
        $this->view = new View();
    }

    public function run(array $pages =["add", "tree"]):void{
        $this->view->render($pages, ["list" =>$this->ViewTree()]);
    }

    public function ViewTree():array {

        return $this->database->downloadChildrenTree();
    }
}