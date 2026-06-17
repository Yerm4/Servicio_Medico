<?php
namespace app\controller;

use app\model\Usuario;

class Controller {

    private $pdo;
    public function __construct($conexion){
        $this->pdo = $conexion;
    }
    public function consultar () {
        $consulta = new Usuario($this->pdo);
        return $consulta->consultarUsuarios();
    }
    public function login() {
        $cedula = isset($_POST["cedula"]) ? trim($_POST["cedula"]) : "";
        $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

        if (!empty($cedula) && !empty($password) && strlen($cedula) >= 7 && strlen($cedula) <= 8) {
            $model = new Usuario($this->pdo);
            $usuarioEncontrado = $model->loginUsuario($cedula);
            if ($usuarioEncontrado && password_verify($password, $usuarioEncontrado["contrasena"])) {
                session_regenerate_id(true);
                $_SESSION["login_notif"] = "Logueado";
                $_SESSION["cedula"] = $usuarioEncontrado["cedula"];
                $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
                header("Location: perfil");
                exit();
            }
            

            else {
                $_SESSION["login_notif"] = "Login no exitoso. Usuario o contraseña incorrectos";
            }
        }

        else {
            $_SESSION["login_notif"] = "Login no exitoso. No pasaste las validaciones";
        }
    }

    public function eliminar() {

        header('Content-Type: application/json');
        $cedula = isset($_POST['id']) ? trim($_POST['id']) : '';
    
        if (!empty($cedula)) {
            
            $model = new Usuario($this->pdo);
            
            $seBorro = $model->eliminarUsuario($cedula);
            
            if ($seBorro) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el registro en la base de datos.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cédula no válida o vacía.']);
        }
        
        exit();
    }
    
    public function buscar() {
        header('Content-Type: application/json');

        // Capturamos lo que el usuario escribió en la barra
        $query = isset($_POST['query']) ? trim($_POST['query']) : '';

        $model = new Usuario($this->pdo);

        // Si la barra está vacía, volvemos a traer los 5 por defecto
        if (empty($query)) {
            $resultados = $model->consultarUsuarios(); // El método que ya tenías para los últimos 5
        } else {
            // Si escribió algo, llamamos al nuevo método de búsqueda
            $resultados = $model->buscarUsuarios($query);
        }

        // Le devolvemos la lista limpia a JavaScript
        echo json_encode($resultados);
        exit();
    }
}