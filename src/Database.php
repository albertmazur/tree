<?php

namespace App;

use App\Exception\ConfigurationException;
use PDO;
use PDOException;

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

    public function first(): array{
        $query = "SELECT * FROM kategorie";

        $result = $this->conn->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function  getElement(int $id):array{
        $query = "SELECT * FROM kategorie WHERE id={$id}";

        $result = $this->conn->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function  getNextElement(int $id):array{
        $query = "SELECT * FROM kategorie WHERE id_prev={$id}";
        $result = $this->conn->query($query);

        if($result->rowCount()>0)return $result->fetch(PDO::FETCH_ASSOC);
        else return [];
    }

    public function  downloadChildrenTree(int $id = null):array{
        $query = "SELECT * FROM kategorie WHERE id_rodzic";
        if($id==null) $query .= " IS NULL";
        else $query .= "=$id";
        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addElement(array $conf): int{
        if($conf['id_prev']!='null') $query = "INSERT INTO kategorie VALUES(null, {$this->conn->quote($conf['nazwa'])}, {$this->conn->quote($conf['id_rodzic'])}, {$this->conn->quote($conf['id_prev'])})";
        else $query = "INSERT INTO kategorie VALUES(null, {$this->conn->quote($conf['nazwa'])}, {$this->conn->quote($conf['id_rodzic'])}, null)";
        if($conf['id_rodzic']=='0'){
            $query = "INSERT INTO kategorie VALUES(null, {$this->conn->quote($conf['nazwa'])}, null, null)";
            $this->conn->exec($query);
            $result = $this->first();
            return $result['id'];
        }

        $this->conn->exec($query);
        return $this->returnElement($conf);
    }

    private function returnElement(array $conf): int{
        $query = "SELECT id FROM  kategorie WHERE  nazwa={$this->conn->quote($conf['nazwa'])} AND id_rodzic={$this->conn->quote($conf['id_rodzic'])}";

        $result = $this->conn->query($query);
        $id = $result->fetch(PDO::FETCH_ASSOC);
        return $id['id'];
    }

    public function updateNextElement(int $id, int $id_prev): void{
        $query = "UPDATE kategorie SET id_prev={$id_prev} WHERE id={$id}";
        $this->conn->exec($query);
    }

    public function updateParentElement(int $id, int $id_rodzic): void{
        $query = "UPDATE kategorie SET id_rodzic={$id_rodzic} WHERE id={$id}";
        $this->conn->exec($query);
    }

    public function updateNazwaElement(int $id, string $name): void{
        $query = "UPDATE kategorie SET nazwa={$this->conn->quote($name)} WHERE id={$id}";
        $this->conn->exec($query);
    }

    public function removeElement(int $id): void{
        $element = $this->getElement($id);
        $elementNext = $this->getNextElement($id);

        if(count($elementNext)>0) $this->updateNextElement((int)$elementNext['id'], (int)$element['id_prev']);

        $query = "DELETE FROM kategorie WHERE id_rodzic={$this->conn->quote($id)}";
        $this->conn->exec($query);

        $query = "DELETE FROM kategorie WHERE id={$this->conn->quote($id)}";
        $this->conn->exec($query);
    }
}