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


      public function show():void{
        $resultado = $this->model->show();
        if($resultado){
          $this->json(['status'=>'success', "data"=>$resultado], 200);
          return;
        }else{
            $this->json(["status"=>"error", "msg"=>"Json inválido"], 400);
          return;
        }
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
        return;
      }else{
        $this->json(['status'=> 'error', 'msg'=>'No se pudo crear usuario'], 500);
        return;
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


    public function session():void{
      session_start();

      if(!isset($_SESSION['id_user'])){
        $this->json(['authenticated' => false], 401);
        return;
      }

      $this->json([
        'authenticated'=>true,
        'user'=>[
          'id_user'=> $_SESSION['id_user'],
          'nombre_user'=>$_SESSION['nombre_user']
        ]
      ],200);
    }

    public function logout(): void {
      session_start();
      session_destroy();

     $this->json([
      'status' => 'success',
      'msg' => 'Sesión cerrada correctamente'
      ], 200);
  }

    //Para que funcione el json
    private function json($data, $statuscode=200):void{
      http_response_code($statuscode);
      header('Content-Type: application/json');
      echo json_encode($data);
    }
  }

?>