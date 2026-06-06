<?php
namespace app\controller;

use app\model\Usuario;

class Controller {

    private $pdo;
    public function __construct($conexion){
        $this->pdo = $conexion;
    }

    public function registrar() {
        $cedula = isset($_POST["cedula"]) ? trim($_POST["cedula"]) : "";
        $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

        if (!empty($cedula) && !empty($password) && strlen($cedula) >= 7 && strlen($cedula) <= 8) {
            $password_hash = password_hash($password, PASSWORD_ARGON2I);
            $model = new Usuario($this->pdo);
            $registroExitoso = $model->registrarUsuario($cedula, $password_hash);

            if ($registroExitoso) {
                $_SESSION["registro_notif"] = "Registro correcto";
            }

            else {
                $_SESSION["registro_notif"] = "Registro no exitosoaaa";
            }
        }

        else {
            $_SESSION["registro_notif"] = "Registro no exitoso. No pasaste las validaciones";
        }
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

    public function registrarConsulta() {
        
    }
}