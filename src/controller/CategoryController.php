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

    public function __construct(array $configuration ){
        if(empty($configuration['db'])) throw new ConfigurationException("Configuration error");

        $this->database = new Database($configuration['db']);
        $this->view = new View();
    }

    public function run(array $pages = self::DEFAULT_ACTION):void{
        if(isset($pages['action'])){
            if($pages['action']=="ajax") $this->ajax();
            if($pages['action']=="add") $this->add();
            if($pages['action']=="remove") $this->remove();
            if($pages['action']=="edit") $this->edit();
        }
        else $this->view->render($pages, ["list" =>$this->ViewTree()]);
    }

    public function ViewTree():array {
        return $this->database->downloadChildrenTree();
    }

    public function ajax():void{
        if(isset($_POST['id'])){
            echo json_encode($this->database->downloadChildrenTree($_POST['id']));
        }
    }

    public function add():void{
        if(isset($_POST['nazwa']) && isset($_POST['id_rodzic'])){
            $id_rodzic = (int) $_POST['id_rodzic'];
            echo $this->database->addElement(["nazwa" => $_POST['nazwa'], "id_rodzic" => $id_rodzic , "id_prev" => $_POST['id_prev']]);
        }
    }

    public function remove(): void{
        if(isset($_POST['id'])){
            $this->database->removeElement($_POST['id']);
        }
    }

    public function edit(): void{
        if(isset($_POST['id'])){
            $element = $this->database->getElement($_POST['id']);
            $p = self::DEFAULT_ACTION;
            array_push($p, "form");
            $this->view->render($p, ["list" =>$this->ViewTree(), "element" => $element]);
        }
    }
}