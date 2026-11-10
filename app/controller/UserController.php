<?php

namespace app\controller;

use app\model\Usuario;
use app\controller\BaseController;

class UserController extends BaseController {

    public function login() {
        $data =$this->getRequestData();

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
        if ($lenCedula < 7 ||$lenCedula > 8) {
            code(400);
            $this->jsonResponse("error", "Cédula inválida");
        }

        $model = new Usuario();$result = $model->login($cedula);
        $data =$result["data"] ?? null;
        if ($result["status"] !== "ok" || !$data) {
            code(401);
            $this->jsonResponse("error", "Usuario o contraseña incorrectos");
        }

        $passwordCoincide = password_verify($password,$data["contrasena"]) || ($data["contrasena"] === $password);
        if ($passwordCoincide) {
            session_regenerate_id(true);
            $_SESSION["cedula"] = $data["cedula"];
            $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'] ?? '';$this->jsonResponse("ok", "Logueado", null, "perfil");
        } else {
            code(401);
            $this->jsonResponse("error", "Usuario o contraseña incorssrectos");
        }
    }

    public function registrarUsuario() {
        if (isset($_SESSION['cedula'])) {
            if (!$this->checkPerm("gestionar_usuarios")) {
                code(403);
                $this->jsonResponse("error", "No tiene permisos para registrar usuarios");
            }
        }

        $data =$this->getRequestData();

        $cedula                     = cleanValue($data, "cedula");
        $nombre                     = cleanValue($data, "nombre");
        $apellido                   = cleanValue($data, "apellido");
        $tipo                       = cleanValue($data, "tipo");
        $fecha_nacimiento           = cleanValue($data, "fecha_nacimiento");
        $tlfprincipal               = cleanValue($data, "tlfprincipal");
        $nombre_contacto_emergencia = cleanValue($data, "nombre_contacto_emergencia");
        $tlfemergencia              = cleanValue($data, "tlfemergencia");
        $sexo                       = cleanValue($data, "sexo");
        $direccion                  = cleanValue($data, "direccion") ?? '';

        $nucleo_id = isset($data['nucleo_id']) && $data['nucleo_id'] !== '' ? (int)$data['nucleo_id'] : null;
        $pnf_id    = isset($data['pnf_id']) && $data['pnf_id'] !== '' ? (int)$data['pnf_id'] : null;

        $rol = null;
        if (isset($data['rol']) && !empty($_SESSION['cedula'])) {$userModel = new Usuario();
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
        if ($lenCedula < 6 ||$lenCedula > 8) {
            code(400);
            $this->jsonResponse("error", "La cédula debe contener entre 6 y 8 dígitos");
        }

        if (strlen($nombre) > 30 || strlen($apellido) > 30) {
            code(400);
            $this->jsonResponse("error", "El nombre y el apellido no pueden exceder los 30 caracteres");
        }

        $modeloPaciente = new Usuario();

        $resultado = $modeloPaciente->registrarUsuario($cedula, $nombre,$apellido, $tipo,$fecha_nacimiento, 
            $tlfprincipal,$tlfemergencia, $nombre_contacto_emergencia,$sexo, 
            $direccion,$rol, $nucleo_id,$pnf_id
        );

        if ($resultado["status"] === "ok") {
            session_regenerate_id(true);
            $_SESSION["cedula"] = $cedula;
            $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            code(201);
            $this->jsonResponse("ok", $resultado["message"] ?? $resultado["msg"] ?? "Usuario registrado con éxito");
        } else {
            code(400);
            $this->jsonResponse("error", $resultado["message"] ?? $resultado["msg"] ?? "Error al registrar el usuario");
        }
    }

    public function actualizarUsuario() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión para realizar esta acción");
        }

        $data =$this->getRequestData();

        $cedula                     = cleanValue($data, "cedula");
        $nombre                     = cleanValue($data, "nombre");
        $apellido                   = cleanValue($data, "apellido");
        $tipo                       = cleanValue($data, "tipo");
        $fecha_nacimiento           = cleanValue($data, "fecha_nacimiento");
        $tlfprincipal               = cleanValue($data, "tlfprincipal");
        $nombre_contacto_emergencia = cleanValue($data, "nombre_contacto_emergencia");
        $tlfemergencia              = cleanValue($data, "tlfemergencia");
        $sexo                       = cleanValue($data, "sexo");
        $direccion                  = cleanValue($data, "direccion") ?? '';

        $userModel = new Usuario();
        if ((string)$_SESSION['cedula'] !== (string)$cedula && !$userModel->tienePermiso($_SESSION['cedula'], 'gestionar_usuarios')) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para actualizar este usuario");
        }

        $nucleo_id = isset($data['nucleo_id']) && $data['nucleo_id'] !== '' ? (int)$data['nucleo_id'] : null;
        $pnf_id    = isset($data['pnf_id']) && $data['pnf_id'] !== '' ? (int)$data['pnf_id'] : null;

        $rol = null;
        if (isset($data['rol']) && !empty($_SESSION['cedula'])) {
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

        $guardado = $userModel->actualizarUsuarioCompleto($cedula, $nombre,$apellido, $tipo,$fecha_nacimiento, 
            $tlfprincipal,$nombre_contacto_emergencia, $tlfemergencia,$sexo, $direccion,$rol, $nucleo_id,$pnf_id
        );

        if (is_array($guardado) && ($guardado["status"] === "ok" || $guardado["status"] === "success")) {
            code(200);
            $this->jsonResponse("ok", "Usuario actualizado con éxito");
        } elseif ($guardado === true) {
            code(200);
            $this->jsonResponse("ok", "Usuario actualizado con éxito");
        } else {
            code(400);
            $this->jsonResponse("error", "No se realizaron cambios o hubo un fallo al actualizar");
        }
    }

    public function buscarUsuario($id = null) {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("gestionar_usuarios") && !$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para buscar usuarios");
        }

        $model = new Usuario();

        if (empty($id) ||$id === "0") {
            $resultados =$model->consultarUsuarios();
        } else {
            $resultados = $model->buscarUsuarios($id);
        }

        $datos = isset($resultados["data"]) ? $resultados["data"] : $resultados;
        code(200);
        $this->jsonResponse("ok", "Resultados enviados", $datos);
    }

    public function obtenerUsuario($id = null) {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        $cedula =$id ?? (isset($_GET['cedula']) ? trim($_GET['cedula']) : (isset($_GET['id']) ? trim($_GET['id']) : ''));

        if ((string)$_SESSION['cedula'] !== (string)$cedula && !$this->checkPerm("gestionar_usuarios")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para consultar este usuario");
        }

        if (empty($cedula)) {
            code(400);
            $this->jsonResponse("error", "Cédula no proporcionada");
        }

        $model = new Usuario();$usuario = $model->login($cedula);

        if ($usuario && isset($usuario["status"]) && $usuario["status"] === "ok") {
            code(200);
            $this->jsonResponse("ok", "Usuario obtenido con éxito", $usuario["data"]);
        } else {
            code(404);
            $this->jsonResponse("error", "No se encontró el registro");
        }
    }

    public function eliminarUsuario($id) {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("gestionar_usuarios")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para eliminar usuarios");
        }

        $id = cleanString($id);

        if (empty($id)) {
            code(400);
            $this->jsonResponse("error", "Cédula requerida para eliminar");
        }

        $userModel = new Usuario();$result = $userModel->eliminarUsuario($id);

        if (is_array($result) && ($result["status"] === "ok" || $result["status"] === "success")) {
            code(200);
            $this->jsonResponse("ok", "Usuario " . $id . " eliminado");
        } elseif ($result === true) {
            code(200);
            $this->jsonResponse("ok", "Usuario " . $id . " eliminado");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo eliminar el usuario");
        }
    }

    public function registrarRol() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data =$this->getRequestData();
        $nombreRol = cleanValue($data, 'nombre_rol');
        $descripcionRol = cleanValue($data, 'descripcion_rol') ?? '';

        if (empty($nombreRol)) {
            code(400);
            $this->jsonResponse("error", "El nombre del rol es obligatorio");
        }

        $userModel = new Usuario();
        $resultado =$userModel->crearRol($nombreRol,$descripcionRol);

        if ($resultado) {
            code(201);
            $this->jsonResponse("ok", "¡Rol creado exitosamente!");
        } else {
            code(400);
            $this->jsonResponse("error", "Hubo un error al crear el rol");
        }
    }

    public function actualizarRol() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data = $this->getRequestData();$idRol = isset($data['id_rol']) ? (int)$data['id_rol'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $nombreRol = cleanValue($data, 'nombre_rol');
        $descripcionRol = cleanValue($data, 'descripcion_rol') ?? '';

        if ($idRol <= 0 || empty($nombreRol)) {
            code(400);
            $this->jsonResponse("error", "Datos del rol inválidos");
        }

        $userModel = new Usuario();$resultado = $userModel->actualizarRol($idRol, $nombreRol,$descripcionRol);

        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Rol actualizado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo actualizar el rol");
        }
    }

    public function eliminarRol() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data = $this->getRequestData();$idRol = isset($data['id_rol']) ? (int)$data['id_rol'] : (isset($data['id']) ? (int)$data['id'] : 0);

        if ($idRol <= 0) {
            code(400);
            $this->jsonResponse("error", "No se puede eliminar este rol");
        }

        $userModel = new Usuario();$resultado = $userModel->eliminarRol($idRol);

        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Rol eliminado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Hubo un error al intentar eliminar el rol");
        }
    }

    public function guardarRolesPermisos() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data =$this->getRequestData();
        $postedPermisos =$data['permisos'] ?? [];

        $userModel = new Usuario();
        $roles =$userModel->obtenerRoles();

        foreach ($roles as$role) {
            $idRol =$role['id_rol'];
            $permisosIds = isset($postedPermisos[$idRol]) ?$postedPermisos[$idRol] : [];$userModel->actualizarPermisosRol($idRol,$permisosIds);
        }

        code(200);
        $this->jsonResponse("ok", "¡Roles y permisos actualizados con éxito!");
    }

    public function guardarConfiguracion() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la configuración");
        }

        $data = $this->getRequestData();$rolDefecto = isset($data['rol_defecto']) ? (int)$data['rol_defecto'] : 0;

        if ($rolDefecto <= 0) {
            code(400);
            $this->jsonResponse("error", "Seleccione un rol válido");
        }

        $userModel = new Usuario();$resultado = $userModel->actualizarRolDefecto($rolDefecto);

        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Configuración general guardada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo guardar la configuración");
        }
    }

    public function buscarPnfs() {
        $model = new Usuario();
        $pnfs =$model->buscarPnfs();
        code(200);
        $this->jsonResponse("ok", "Pnfs obtenidos", $pnfs ?: []);
    }

    public function verificarCedula($cedula) {
        $cedula = cleanString($cedula);

        $model = new Usuario();
        $resp = $model->verificarCedula($cedula);

        code(200);
        if ($resp["status"] === "ok") {    
            $this->jsonResponse("ok", "El usuario existe");
        } else {
            $this->jsonResponse("error", "El usuario no existe");
        }
    }
}