<?php

namespace App\Controller;


use App\Database;
use App\Exception\ConfigurationException;
use App\View;

class CategoryController
{
    private const DEFAULT_ACTION = ["tree"];

    private Database $database;
    private View $view;
    private static int $id;

    public static function getId(int $id){
        self::$id=$id;
    }

    public function getChildren (int $id):array {
        return $this->database->downloadChildrenTree($id);
    }

    public function __construct(array $configuration ){
        if(empty($configuration['db'])) throw new ConfigurationException("Configuration error");

        $this->database = new Database($configuration['db']);
        $this->view = new View();
    }

    public function run(array $pages = self::DEFAULT_ACTION):void{
        if(isset($pages['action'])){
            if($pages['action']=="ajax") $this->ajax();
        }
        else$this->view->render($pages, ["list" =>$this->ViewTree()]);
    }

    public function ViewTree():array {
        if(isset(self::$id)){
            var_dump(self::$id);
        }
        return $this->database->downloadChildrenTree();
    }

    public function ajax():void{
        if(isset($_POST['id'])){
            echo json_encode($this->database->downloadChildrenTree($_POST['id']));
        }
    }
}