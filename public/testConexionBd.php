<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\database\Conexion;

try{
    Conexion::contectar();
    echo 'CONEXIÓN ÉXITOSA';

}catch(PDOException $e){
    echo 'Error de conexión: ' . $e->getMessage();
}


?>