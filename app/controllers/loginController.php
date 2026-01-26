<?php
  namespace App\controllers;

  use App\models\LoginModel;
  use App\models\AuditoriaLogModel;

  class LoginController{
     private $model;
     private $modelLog;

     public function __construct()
     {
        $this->model = new LoginModel();
        $this->modelLog = new AuditoriaLogModel();
     }

      public function store():void{
      //Leer json
      $data = json_decode(file_get_contents('php://input'), true);
      if($data === null){
          $this->json(["status"=>"error", "msg"=>"Json inválido"], 400);
          return;
      }
      //validación
      if(empty($data['nombre_user']) || empty($data['correo_user']) || empty($data['password_user'])){
          $this->json(['status'=>'error', 'msg'=> 'Datos incompletos'],400);
          return;
      }

      $resultado = $this->model->create(
        $data['nombre_user'], 
        $data['correo_user'], 
        $data['password_user']
      );

      if($resultado){
        $this->json(['status'=> 'success', 'msg'=>'Usuario creado con éxito'], 201);
      }else{
        $this->json(['status'=> 'error', 'msg'=>'No se pudo crear usuario'], 500);
      }
    }



    //login
    public function login():void{
      $data = json_decode(file_get_contents('php://input'),true);

      if($data === null){
          $this->json(["status"=>"error", "msg"=>"Json inválido"], 400);
          return;
      }

      //Validación
      if(empty($data['correo_user']) || empty($data['password_user'])){
          $this->json(['status'=>'error','msg'=>'Credenciales incompletas'], 400);
        return;
      }

      $user = $this->model->login($data['correo_user'], $data['password_user']);
      if(!$user){
          $this->json(['status'=>'error', 'msg'=>'Datos no coinciden'], 401);
          return;
      }

      //Si todo va bien
      session_start();
      $_SESSION['id_user'] = $user['id_user'];
      $_SESSION['nombre_user'] = $user['nombre_user'];

      $this->modelLog->registrarLoginLog($user['id_user'], $user['nombre_user'], $_SERVER['REMOTE_ADDR']);
        $this->json(['status'=>'success','msg'=>'Login correcto'], 200);
    }

    //Para que funcione el json
    private function json($data, $statuscode=200):void{
      http_response_code($statuscode);
      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

?>