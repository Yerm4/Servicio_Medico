<?php

namespace app\controller;

use app\controller\ConsultaController;
use app\controller\CondicionController;
use app\controller\NucleoPnfController;
use app\controller\UserController;

class ApiController {
    public function __construct($endpoint) {
        
        $controllerName = null;
        $metodo = null;
        $parametros = [];

        $rutasApi = require __DIR__."/../api.php";
        $httpMethod = !empty($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
        
        if (isset($rutasApi[$httpMethod])) {
            foreach ($rutasApi[$httpMethod] as $patronRuta => $accion) {
                $patronRegex = "#^".preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $patronRuta)."$#";
                if (preg_match($patronRegex, $endpoint, $coincidencias)) {
                    [$controllerName, $metodo] = explode("@", $accion);
                    array_shift($coincidencias);
                    $parametros = $coincidencias;
                    
                    break;
                }
            }
        }
        
        if ($controllerName && $metodo) {
            header("Content-Type: application/json; charset=UTF-8");
            $controllerName = "app\\controller\\".$controllerName;
            $controller = new $controllerName();
            if (method_exists($controller, $metodo)) {
                
                $controller->$metodo(...$parametros);
                exit();
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "El metodo ".$metodo." del controlador no existe"]);
                exit();
            }
        }
        if (str_starts_with($endpoint, "api/")) { 
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(404); 
            echo json_encode([ 
                "status" => "error", 
                "message" => "La ruta: " . $endpoint . " no existe" 
            ]); 
            exit(); 
        }
        
    }
}