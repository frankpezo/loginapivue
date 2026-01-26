<?php
  namespace App\database;

  use PDO;
  use PDOException;
  use App\config\Config;

  class Conexion{
      private static ?PDO $pdo = null;

      public static function contectar(): PDO{
           if(self::$pdo == null){
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s; charset=%s",
                    Config::DB_HOST, 
                    Config::DB_NAME, 
                    Config::DB_CHARSET
                );

                try{
                    self::$pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS,[
                        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, 
                        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, 
                        PDO::ATTR_EMULATE_PREPARES=>false,
                    ]);

                }catch(PDOException $e){
                  throw new \RuntimeException("Error de conexión a la base de datos");
                }
           }
           return self::$pdo;
      }
  }


?>