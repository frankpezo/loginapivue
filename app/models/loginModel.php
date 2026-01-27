<?php
 namespace App\models;

 use PDO;
 use App\database\Conexion;

 class LoginModel{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::contectar();
    }

    public function create(String $nombre_user, string $correo_user, string $password_user){
        $passwordHash = password_hash($password_user, PASSWORD_BCRYPT);
        $sql = "INSERT INTO user(nombre_user, correo_user, password_user) VALUES(:nombre,:correo,:pass)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
           ':nombre' =>$nombre_user, 
            ':correo'=>$correo_user, 
           ':pass' =>$passwordHash]);
    }

    public function login(string $correo_user, string $password_user){
        $sql = "SELECT * FROM user WHERE correo_user =:correo_user LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':correo_user'=>$correo_user]);

        $user =  $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return false;
        }

        if(!password_verify($password_user,$user['password_user'])){
            return false;
        }

          unset($user['password_user']); // nunca exponer el hash

    return $user;
    }


 }

 
?>