<?php

use App\Exception\ConfigurationException;

function createDatabaseAndTables(PDO $pdo, string $dbName, string $dbTable): void
{
    try {
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['dbName' => $dbName]);
        $dbExists = $stmt->fetchColumn();

        if (!$dbExists) {
            $pdo->exec("CREATE DATABASE `$dbName`");
            $pdo->exec("USE `$dbName`");
            $createTableQuery = "
                CREATE TABLE {$dbTable} (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `nazwa` text NOT NULL,
                    `id_rodzic` int(11) DEFAULT NULL,
                    `id_prev` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ";
            $pdo->exec($createTableQuery);
        }
    } catch (PDOException $e) {
        throw new ConfigurationException("Błąd podczas tworzenia bazy danych i tabeli".$e->getMessage());
    }
}