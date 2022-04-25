<?php

namespace App\Controller;

use App\Database;
use App\Exception\ConfigurationException;
use App\View;

class CategoryController
{
    private const DEFAULT_ACTION = ["tree", "form"];

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
            if($pages['action']=="up") $this->up();
        }
        else $this->view->render($pages, ["list" =>$this->ViewTree()]);
    }

    public function ViewTree():array {
        return  $this->sort($this->database->downloadChildrenTree());
    }

    private function sort(array $list):array{
        $newList = [];
        foreach ($list as $e){
            if($e["id_prev"]== null){
                $newList[] = $e;
                $li = $e;
            }
        }
        for($i = 0; $i<count($list); $i++){
            if($li['id']==($list[$i]['id_prev'])){
                $newList[] = $list[$i];
                $li = $list[$i];
                $i=-1;
            }
        }
        return $newList;
    }

    public function ajax():void{
        if(isset($_POST['id'])){
            echo json_encode($this->sort($this->database->downloadChildrenTree($_POST['id'])));
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

    public function edit():void{
        if(!empty($_POST['id']) && isset($_POST['nazwa']) && isset($_POST['id_rodzic']) && isset($_POST['id_prev']) && isset($_POST['id_next']) && isset($_POST['id_n']) && isset($_POST['id_r'])){
            if(!empty($_POST['nazwa'])) $this->database->updateNazwaElement((int) $_POST['id'], $_POST['nazwa']);
            if(!empty($_POST['id_prev'])) $this->database->updateNextElement((int) $_POST['id'], (int) $_POST['id_prev']);
            if(!empty($_POST['id_next'])) $this->database->updateNextElement((int) $_POST['id_next'], (int) $_POST['id']);
            if(!empty($_POST['id_rodzic'])) $this->database->updateParentElement((int) $_POST['id'], (int) $_POST['id_rodzic']);
            if(!empty($_POST['id_n']) && !empty($_POST['id_next']) && empty($_POST['id_r'])) $this->database->updateNextElement((int) $_POST['id_n'], (int) $_POST['id_next']);
            if(!empty($_POST['id_n']) && !empty($_POST['id_next']) && !empty($_POST['id_r'])) $this->database->updateNextElement((int) $_POST['id_n'], (int) $_POST['id_r']);
        }
        $this->view->render(self::DEFAULT_ACTION, ["list" =>$this->ViewTree()]);
    }

    public function up():void{
        if(isset($_POST['id']) && isset($_POST['id_prev'])){
            $this->database->updateNextElement((int) $_POST['id'], (int) $_POST['id_prev']);
        }
    }
}