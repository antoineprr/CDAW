<?php
define('_MYSQL_HOST','127.0.0.1');
define('_MYSQL_PORT',3306);
define('_MYSQL_DBNAME','dbtest');
define('_MYSQL_USER','root');
define('_MYSQL_PASSWORD','');

$connectionString = "mysql:host=". _MYSQL_HOST;

if(defined('_MYSQL_PORT'))
    $connectionString .= ";port=". _MYSQL_PORT;

$connectionString .= ";dbname=" . _MYSQL_DBNAME;
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );

try {
    $pdo = new PDO($connectionString,_MYSQL_USER,_MYSQL_PASSWORD,$options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $erreur) {
        myLog('Erreur : '.$erreur->getMessage());
}