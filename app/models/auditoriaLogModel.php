<?php
namespace App\models;

use PDO;
use App\database\Conexion;

class AuditoriaLogModel{
  private $pdo;

  public function __construct()
  {
     $this->pdo = Conexion::contectar();

  }

  public function registrarLoginLog(int $user_id,string $username, string $ip_address){
     $sql = "INSERT INTO audit_logs(user_id, username, ip_address) VALUES(:user_id,:username,:ip)";
     $stmt = $this->pdo->prepare($sql);
     $stmt->execute([
       ':user_id'=> $user_id, 
       ':username'=> $username, 
        ':ip'=>$ip_address]);
     }


}



?>