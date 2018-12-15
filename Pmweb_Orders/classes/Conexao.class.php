<?php

class Conexao {

    private static $pdo;

    public function __construct() {
        
    }

    public function conectar() {

        include_once "config.php";

        try {
            if (is_null(self::$pdo)) {
                self::$pdo = new PDO("mysql:host=" . MYSQL_HOSTNAME . 
                        ";dbname=" . MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD);
            }
            return self::$pdo;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

}

?>
