<?php

class SqliteConnection {

    const PATH_TO_DB =  "sqlite:D:/Program_files/xampp/htdocs/HP/resources/hp.db";

    private $pdo;

    public function connect() {
        if ($this->pdo == null) {
            try {
                $this->pdo = new PDO(self::PATH_TO_DB);
            } catch (Exception $e) {
                echo "! Sikertelen csatlakozás az adatbázishoz !";
            }
        }
        return $this->pdo;
    }
}