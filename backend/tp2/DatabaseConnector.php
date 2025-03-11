<?php
require_once "config.php";

class DatabaseConnector {

    protected static $pdo = NULL;

    public static function current(){
       if(is_null(static::$pdo))
          static::createPDO();

       return static::$pdo;
    }

    protected static function createPDO() {
        $connectionString = "mysql:host=". _MYSQL_HOST;

        if(defined('_MYSQL_PORT'))
            $connectionString .= ";port=". _MYSQL_PORT;

        $connectionString .= ";dbname=" . _MYSQL_DBNAME;
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

        try {
            static::$pdo = new PDO($connectionString, _MYSQL_USER, _MYSQL_PASSWORD, $options);
            static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $erreur) {
            // Log or handle the error appropriately
            echo 'Connection error: ' . $erreur->getMessage();
            exit;
        }
    }
}