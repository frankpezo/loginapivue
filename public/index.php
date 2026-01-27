<?php
 declare(strict_types=1);

 require_once __DIR__ . '/../vendor/autoload.php';

 use App\routers\RouterLogin;

 // Cabeceras base para API, esto nos permitirá acceder desde VUe y otros framwefowks 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


// Ejecutar router
RouterLogin::run();

// Seguridad: detener cualquier ejecución adicional
exit;


?>