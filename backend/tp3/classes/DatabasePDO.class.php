<?php

class DatabasePDO extends PDO {

   protected static $singleton = NULL;

   public static function singleton(){
      if(is_null(static::$singleton))
         static::$singleton = new static();

      return static::$singleton;
   }

   public function __construct() {
	   // $db = new PDO("sqlite::memory");

	   $connectionString = "mysql:host=". _MYSQL_HOST;

	   if(defined('DB_PORT'))
		   $connectionString .= ";port=". _MYSQL_PORT;

	   $connectionString .= ";dbname=" . _MYSQL_DBNAME;
	   $connectionString .= ";charset=utf8";

      parent::__construct($connectionString,_MYSQL_USER,_MYSQL_PASSWORD);
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }
}