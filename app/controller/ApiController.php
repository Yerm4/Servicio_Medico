<?php

namespace app\controller;

use app\model\NucleoPNF;
use app\model\Usuario;

class ApiController {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    private function jsonResponse($status, $message = "", $data = null, $redirect = null) {
        header("Content-Type: application/json");
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "redirect" => $redirect
        ]); exit();
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        $cedula = cleanValue($data, "cedula");
        $password = cleanValue($data, "password");


        if (empty($cedula) || empty($password)) {
            code(400);
            $this->jsonResponse("error", "Parece que intentaste enviar un campo vacío");
        }

        if (strlen($password) > 20) {
            code(400);
            $this->jsonResponse("error", "Contraseña invalida");
        }

        $lenCedula = strlen((string)$cedula);
        if ($lenCedula < 7 || $lenCedula > 8) {
            code(400);
            $this->jsonResponse("error", "Cédula inválida");
        }

        $model = new Usuario($this->pdo);
        $result = $model->login($cedula);
        $data = $result["data"];
        if ($result["status"] === "ok" && password_verify($password, $data["contrasena"])) {
            session_regenerate_id(true);
            $_SESSION["cedula"] = $result["data"]["cedula"];
            $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];
            $this->jsonResponse("ok", "Logueado", null, "Perfil");
        }

        else {
            $this->jsonResponse("error", $result["status"]);
        }
    }

    public function registrarUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        $cedula                     = cleanValue($data, "cedula");
        $nombre                     = cleanValue($data, "nombre");
        $apellido                   = cleanValue($data, "apellido");
        $tipo                       = cleanValue($data, "tipo");
        $fecha_nacimiento          = cleanValue($data, "fecha_nacimiento");
        $tlfprincipal               = cleanValue($data, "tlfprincipal");
        $nombre_contacto_emergencia = cleanValue($data, "nombre_contacto_emergencia");
        $tlfemergencia              = cleanValue($data, "tlfemergencia");
        $sexo                       = cleanValue($data, "sexo");
        $direccion                  = cleanValue($data, "direccion");
    
        $nucleo_id = isset($data['nucleo_id']) && $data['nucleo_id'] !== '' ? (int)$data['nucleo_id'] : null;
        $pnf_id    = isset($data['pnf_id']) && $data['pnf_id'] !== '' ? (int)$data['pnf_id'] : null;
    
        $rol = null;
        if (isset($data['rol']) && !empty($_SESSION['cedula'])) {
            $userModel = new Usuario($this->pdo);
            if ($userModel->tienePermiso($_SESSION['cedula'], 'gestionar_roles_permisos')) {
                $rol = (int)$data['rol'];
            }
        }
    
        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($tipo) || 
            empty($fecha_nacimiento) || empty($tlfprincipal) || empty($tlfemergencia) || 
            empty($nombre_contacto_emergencia) || empty($sexo) || empty($direccion)) {
            code(400);
            $this->jsonResponse("error", "Por favor completa todos los campos requeridos");
        }
    
        if (!ctype_digit((string)$cedula)) {
            code(400);
            $this->jsonResponse("error", "La cédula solo debe contener números");
        }
    
        $lenCedula = strlen((string)$cedula);
        if ($lenCedula < 6 || $lenCedula > 8) {
            code(400);
            $this->jsonResponse("error", "La cédula debe contener entre 6 y 8 dígitos");
        }
    
        if (strlen($nombre) > 30 || strlen($apellido) > 30) {
            code(400);
            $this->jsonResponse("error", "El nombre y el apellido no pueden exceder los 30 caracteres");
        }
    
        $modeloPaciente = new Usuario($this->pdo);

        $resultado = $modeloPaciente->registrarUsuario(
            $cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, 
            $tlfprincipal, $tlfemergencia, $nombre_contacto_emergencia, $sexo, 
            $direccion, $rol, $nucleo_id, $pnf_id
        );

        if ($resultado["status"] === "ok") {
            code(201);
            $this->jsonResponse("ok", $resultado["msg"] ?? "Usuario registrado con éxito");
        } else {
            code(400);
            $this->jsonResponse("error", $resultado["msg"] ?? "Error al registrar el usuario");
        }
    }

    public function actualizarUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        $cedula                     = cleanValue($data, "cedula");
        $nombre                     = cleanValue($data, "nombre");
        $apellido                   = cleanValue($data, "apellido");
        $tipo                       = cleanValue($data, "tipo");
        $fecha_nacimiento           = cleanValue($data, "fecha_nacimiento");
        $tlfprincipal               = cleanValue($data, "tlfprincipal");
        $nombre_contacto_emergencia = cleanValue($data, "nombre_contacto_emergencia");
        $tlfemergencia              = cleanValue($data, "tlfemergencia");
        $sexo                       = cleanValue($data, "sexo");
        $direccion                  = cleanValue($data, "direccion");
    
        $nucleo_id = isset($data['nucleo_id']) && $data['nucleo_id'] !== '' ? (int)$data['nucleo_id'] : null;
        $pnf_id    = isset($data['pnf_id']) && $data['pnf_id'] !== '' ? (int)$data['pnf_id'] : null;
    
        $rol = null;
        if (isset($data['rol']) && !empty($_SESSION['cedula'])) {
            $userModel = new Usuario($this->pdo);
            if ($userModel->tienePermiso($_SESSION['cedula'], 'gestionar_roles_permisos')) {
                $rol = (int)$data['rol'];
            }
        }
    
        if (empty($cedula)) {
            code(400);
            $this->jsonResponse("error", "La cédula es requerida para actualizar el usuario");
        }
    
        if (!ctype_digit((string)$cedula)) {
            code(400);
            $this->jsonResponse("error", "La cédula solo debe contener números");
        }
    
        if (strlen($nombre) > 30 || strlen($apellido) > 30) {
            code(400);
            $this->jsonResponse("error", "El nombre y el apellido no pueden exceder los 30 caracteres");
        }
    
        $userModel = new Usuario($this->pdo);
    
        $guardado = $userModel->actualizarUsuarioCompleto(
            $cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, 
            $tlfprincipal, $nombre_contacto_emergencia, $tlfemergencia, 
            $sexo, $direccion, $rol, $nucleo_id, $pnf_id
        );
    
        if ($guardado["status"] === "ok") {
            code(200);
            $this->jsonResponse("ok", "Usuario actualizado con éxito");
        } else {
            code(400);
            $this->jsonResponse("error", "No se realizaron cambios o hubo un fallo al actualizar");
        }
    }

    public function buscarUsuario($id) {
        $model = new Usuario($this->pdo);

        if (empty($id)) {
            $resultados = $model->consultarUsuarios();
        } else {
            $resultados = $model->buscarUsuarios($id);
        }

        $this->jsonResponse("ok", "Resultados enviadoss", $resultados["data"]);
    }

    public function obtenerPnfsPorNucleo($id) {

        if ($id <= 0) {
            $this->jsonResponse("error", "La id no es valida");
        }
        $pnfModel = new NucleoPNF($this->pdo);
        $pnfs = $pnfModel->obtenerPnfsPorNucleo($id);
        if ($pnfs) {
            $this->jsonResponse("ok", "Pnfs enviados", $pnfs);
        }
    }

    public function eliminarUsuario() {
        $data = json_decode(file_get_contents("php://input"), true);
        $idd = $data["cedula"];
        $id = cleanValue($data, "cedula");

        $userModel = new Usuario($this->pdo);

        $data = $userModel->eliminarUsuario($id);

        if ($data["status"] === "ok") {
            $this->jsonResponse("ok", "Usuario ".$id." eliminado");
        } else {
            $this->jsonResponse("error", $idd);
        }
    }

    public function actualizarNucleo() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = isset($data['id_nucleo']) ? (int)$data['id_nucleo'] : 0;
        $nombre = isset($data['nombre_nucleo']) ? trim($data['nombre_nucleo']) : '';

        if ($id <= 0 || empty($nombre)) {
            $this->jsonResponse('error', 'El campo no puede estar vacio.');
        }

            if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            $this->jsonResponse('error', 'El nombre del núcleo debe tener entre 4 y 100 caracteres.');
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
        $this->jsonResponse('error', 'El nombre del núcleo no puede contener números ni caracteres especiales.');
            
        }
        $NucleoModelo = new NucleoPNF($this->pdo);
        $resultado = $NucleoModelo->actualizarNucleo($id, $nombre);

        if ($resultado === "duplicado") {
            $this->jsonResponse('error', 'El nucleo ya se encuentra registrado.');
        } elseif ($resultado) {
            $this->jsonResponse('ok', '¡Núcleo actualizado con éxito!');
        } else {
            $this->jsonResponse('error', 'Ocurrió un error al intentar actualizar el núcleo.');
        }
    }
}

