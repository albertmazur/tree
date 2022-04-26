<?php

namespace App\Controller;

use App\Database;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Request;
use App\View;

class CategoryController
{
    private const DEFAULT_PAGES = ["tree", "form"];
    private const DEFAULT_ACTION = "view";

    private Database $database;
    private View $view;
    private Request $request;

    public function __construct(array $configuration, Request $request){
        if(empty($configuration['db'])) throw new ConfigurationException("Configuration error");

        $this->database = new Database($configuration['db']);
        $this->view = new View();
        $this->request = $request;
    }

    private function action() :string{
        return $this->request->getParam("action", self::DEFAULT_ACTION);
    }

    public function run():void{
        try{
            $action = $this->action()."Action";
            if(!method_exists($this, $action)) $action = self::DEFAULT_ACTION ."ACTION";
            $this->$action();
        }
        catch (StorageException $e){
            var_dump($e->getPrevious());
            $this->view->render(["error"], ['error' => $e->getPrevious()->getMessage()]);
        }
        catch (NotFoundException $e){
            $this->view->render(["error"], ['error' => 'NotFound']);
        }
    }

    public function ViewTree():array {
        return  $this->sort($this->database->downloadChildrenTree());
    }

    public function viewAction($pages = self::DEFAULT_PAGES): void{
        $this->view->render($pages, ["list" =>$this->ViewTree()]);
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

    public function ajaxAction():void{
        if($this->request->postParam("id") !== null){
            echo json_encode($this->sort($this->database->downloadChildrenTree((int) $this->request->postParam("id"))));
        }
    }


    public function addAction():void{
        if($this->request->postParam('nazwa') !== null){
            $id_rodzic = $this->request->postParam("id_rodzic")==0 ? null : $this->request->postParam("id_rodzic");
            $id_prev = $this->request->postParam("id_prev")==0 ? null : $this->request->postParam("id_prev");
            echo $this->database->addElement(["nazwa" => $this->request->postParam('nazwa'), "id_rodzic" => $id_rodzic , "id_prev" => $id_prev]);
        }
    }

    public function removeAction(): void{
        if($this->request->postParam("id") !== null){
            $this->database->removeElement($this->request->postParam("id"));
        }
    }

    public function editAction():void{
        if(!empty($this->request->postParam('id'))){
            $id = (int) $this->request->postParam('id');
            $nazwa = $this->request->postParam('nazwa');
            $id_rodzic = (int) $this->request->postParam('id_rodzic');
            $id_prev = (int) $this->request->postParam('id_prev');
            $id_next = (int) $this->request->postParam('id_next');
            $id_n = (int) $this->request->postParam('id_n');
            $id_r = (int) $this->request->postParam('id_r');

            if(!empty($nazwa)) $this->database->updateNazwaElement($id, $nazwa);
            if(!empty($id_prev)) $this->database->updateNextElement($id, $id_prev);
            if(!empty($id_next)) $this->database->updateNextElement($id_next, $id);
            if(!empty($id_rodzic)) $this->database->updateParentElement($id, $id_rodzic);
            if(!empty($id_rodzic) && !empty($id_next)) $this->database->updateNextElement($id_next, $id_rodzic);
            if(!empty($id_n) && !empty($id_next) && empty($id_r)) $this->database->updateNextElement($id_n, $id_next);
            if(!empty($id_n) && !empty($id_next) && !empty($id_r)) $this->database->updateNextElement($id_n, $id_r);
        }
        $this->view->render(self::DEFAULT_PAGES, ["list" =>$this->ViewTree()]);
    }
}