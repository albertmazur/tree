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

    public function downloadTree(): array{
        $query = "SELECT * FROM kategorie";

        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function  downloadChildrenTree(int $id = null):array{
        if($id==null) $query = "SELECT * FROM kategorie WHERE id_rodzic IS NULL";
        else $query = "SELECT * FROM kategorie WHERE id_rodzic=$id";


        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}