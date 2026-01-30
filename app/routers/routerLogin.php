<?php
namespace App\routers;

use App\controllers\LoginController;

class RouterLogin{
    public  static function run():void{
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $basePath = '/loginapivue/public';

        if(strpos($uri, $basePath) === 0){
             $uri  = substr($uri, strlen($basePath));
        }

        $uri = rtrim($uri, '/');

        //GET
        if($uri ==='/users' && $method=== 'GET'){
            (new LoginController())->show();
            return;
        }

        //POST
        if($uri === '/login' && $method === 'POST'){
            (new LoginController())->login();
            return;
        }

        //POST
        if($uri === '/storeuser' && $method === 'POST'){
            (new LoginController())->store();
            return;
        }


        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status'=>'error', 
            'msg'=> 'Endpoint no encontrado'
        ]);
    }
}


?>