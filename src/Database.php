<?php

namespace App;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;
use Throwable;

class Database{
    private PDO $conn;

    public function __construct(array $config){
        try{
            $this->validateConfig($config);
            $this->createConnection($config);
        }
        catch(PDOException $e){
            throw new ConfigurationException("Connection error", 400, $e);
        }
    }

    private function createConnection(array $config): void{
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO($dsn, $config['user'], $config['password'],[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    private function validateConfig(array $config): void{
        if(empty($config['database']) || empty($config['host'])|| empty($config['user'])|| empty($config['password'])) throw new ConfigurationException("Storage configuration error");
    }

    public function  getElement(int $id):array{
        try{
            $query = "SELECT * FROM kategorie WHERE id={$id}";

            $result = $this->conn->query($query);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się pobrać kategorii", 400, $e);
        }
    }

    public function  getNextElement(int $id):array{
        try{
            $query = "SELECT * FROM kategorie WHERE id_prev={$id}";
            $result = $this->conn->query($query);

            if($result->rowCount()>0)return $result->fetch(PDO::FETCH_ASSOC);
            else return [];
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się pobrać kategorii", 400, $e);
        }
    }

    public function  downloadChildrenTree(int $id = null):array{
        try{
            $query = "SELECT * FROM kategorie WHERE id_rodzic";
            if($id==null) $query .= " IS NULL";
            else $query .= "=$id";
            $result = $this->conn->query($query);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się pobrać kategorii", 400, $e);
        }
    }

    public function addElement(array $conf): int{
        try{
            $query = "INSERT INTO kategorie VALUES (null, {$this->conn->quote($conf['nazwa'])}, {$conf['id_rodzic']}, {$conf['id_prev']})";
            $this->conn->exec($query);
            return $this->returnIdElement($conf);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się dodać kategorii", 400, $e);
        }
    }

    private function returnIdElement(array $conf): int{
        try{
            $query = "SELECT id FROM  kategorie WHERE nazwa={$this->conn->quote($conf['nazwa'])} AND ";

            if($conf['id_rodzic'] == 'null') $query.= "id_rodzic is null AND ";
            else $query.= "id_rodzic= {$conf['id_rodzic']} AND ";

            if($conf['id_prev']== 'null') $query.= "id_prev is null";
            else $query.= "id_prev= {$conf['id_prev']}";
            $result = $this->conn->query($query);
            $id = $result->fetch(PDO::FETCH_ASSOC);
            return $id['id'];
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się pobrać id kategorii", 400, $e);
        }
    }

    public function updateNextElement(int $id, int $id_prev): void{
        try{
            $query = "UPDATE kategorie SET id_prev=";
            if($id_prev<0) $query .= "NULL WHERE id={$id}";
            else $query .= "{$id_prev} WHERE id={$id}";
            $this->conn->exec($query);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się aktualizować kategorii", 400, $e);
        }
    }

    public function updateParentElement(int $id, int $id_rodzic): void{
        try {
            $query = "UPDATE kategorie SET id_rodzic={$id_rodzic} WHERE id={$id}";
            $this->conn->exec($query);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się aktualizować kategorii", 400, $e);
        }
    }

    public function updateNazwaElement(int $id, string $name): void{
        try {
            $query = "UPDATE kategorie SET nazwa={$this->conn->quote($name)} WHERE id={$id}";
            $this->conn->exec($query);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się aktualizować nazwy kategorii", 400, $e);
        }
    }

    public function remove(int $id){
        $query = "DELETE FROM kategorie WHERE id={$this->conn->quote($id)}";
        $this->conn->exec($query);
    }


    public function removeElement(int $id): void{
        try{

            $element = $this->getElement($id);
            $elementNext = $this->getNextElement($id);

            if(count($elementNext)>0) $this->updateNextElement((int)$elementNext['id'], (int)$element['id_prev']);

            $this->removeChildren($id);

            $query = "DELETE FROM kategorie WHERE id={$this->conn->quote($id)}";
            $this->conn->exec($query);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się usunąć kategorii", 400, $e);
        }
    }

    public function removeChildren(int $id): void
    {
        try {
            $query = "DELETE FROM kategorie WHERE id_rodzic={$this->conn->quote($id)}";
            $this->conn->exec($query);
        }
        catch (Throwable $e){
            throw new StorageException("Nie udało się usunąć kategorii", 400, $e);
        }
    }

}