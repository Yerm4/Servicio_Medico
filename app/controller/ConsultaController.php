<?php

namespace app\controller;

use app\model\Consulta;
use app\controller\BaseController;
use app\model\Usuario;
use app\model\Condicion;
use app\controller\CondicionController;
use app\controller\NucleoPnfController;
use app\controller\UserController;

class ConsultaController extends BaseController {

    public function getConsultas($id = null) {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para ver consultas");
        }

        $modeloConsulta = new Consulta();
        $idConsulta = $id ?? (isset($_GET['id']) ? (int)$_GET['id'] : null);
        if ($idConsulta !== null && $idConsulta > 0) {
            $consulta = $modeloConsulta->obtenerConsultaPorId($idConsulta);
            if ($consulta) {
                code(200);
                $this->jsonResponse("ok", "Consulta obtenida con éxito", $consulta);
            } else {
                code(404);
                $this->jsonResponse("error", "No se encontró la consulta médica");
            }
        }

        $query = isset($_GET['query']) ? trim($_GET['query']) : (isset($_GET['q']) ? trim($_GET['q']) : '');
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

        if (empty($query)) {
            $resultados = $modeloConsulta->obtenerConsultasRecientes($limit > 0 ? $limit : 10);
        } else {
            $resultados = $modeloConsulta->buscarConsultas($query, $limit > 0 ? $limit : 20);
        }

        code(200);
        $this->jsonResponse("ok", "Consultas obtenidas con éxito", $resultados ?: []);
    }

    public function buscarConsultas($id = null) {
        $this->getConsultas($id);
    }

    public function obtenerConsulta($id = null) {
        $this->getConsultas($id);
    }

    public function registroConsulta() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("realizar_consulta")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para registrar consultas");
        }

        $data = $this->getRequestData();

        $cedulaPaciente = cleanValue($data, 'cedula_paciente') ?? cleanValue($data, 'cedula');
        $motivo         = cleanValue($data, 'motivo_de_visita') ?? cleanValue($data, 'motivo');
        $observaciones  = cleanValue($data, 'observaciones') ?? '';
        $medicamento    = cleanValue($data, 'medicamento_suministrado') ?? cleanValue($data, 'medicamento') ?? '';

        $sintomas = $data['sintomas'] ?? [];
        if (!is_array($sintomas)) {
            $sintomas = array_filter(array_map('trim', explode(',', (string)$sintomas)));
        }

        $diagnosticos = $data['diagnosticos'] ?? [];
        if (!is_array($diagnosticos)) {
            $diagnosticos = array_filter(array_map('trim', explode(',', (string)$diagnosticos)));
        }

        $condiciones = $data['condiciones'] ?? [];
        if (!is_array($condiciones)) {
            $condiciones = array_filter(array_map('intval', explode(',', (string)$condiciones)));
        }

        $cedulaMedico = $_SESSION['cedula'] ?? '';

        if (empty($cedulaPaciente) || empty($motivo) || empty($cedulaMedico)) {
            code(400);
            $this->jsonResponse("error", "La consulta no fue registrada, revisa que todos los campos requeridos estén llenos");
        }

        $modeloConsulta = new Consulta();
        $resultado = $modeloConsulta->registrarConsulta(
            $cedulaPaciente, $cedulaMedico, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento
        );

        if ($resultado === true) {
            code(201);
            $this->jsonResponse("ok", "La consulta fue registrada exitosamente");
        } else {
            code(400);
            $this->jsonResponse("error", "La consulta no pudo ser registrada");
        }
    }

    public function actualizarConsulta() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("modificar_consulta")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para actualizar consultas");
        }

        $data = $this->getRequestData();

        $idConsulta    = isset($data['id_consulta']) ? (int)$data['id_consulta'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $motivo        = cleanValue($data, 'motivo_de_visita') ?? cleanValue($data, 'motivo');
        $observaciones = cleanValue($data, 'observaciones') ?? '';
        $medicamento   = cleanValue($data, 'medicamento_suministrado') ?? cleanValue($data, 'medicamento') ?? '';

        $sintomas = $data['sintomas'] ?? [];
        if (!is_array($sintomas)) {
            $sintomas = array_filter(array_map('trim', explode(',', (string)$sintomas)));
        }

        $diagnosticos = $data['diagnosticos'] ?? [];
        if (!is_array($diagnosticos)) {
            $diagnosticos = array_filter(array_map('trim', explode(',', (string)$diagnosticos)));
        }

        $condiciones = $data['condiciones'] ?? [];
        if (!is_array($condiciones)) {
            $condiciones = array_filter(array_map('intval', explode(',', (string)$condiciones)));
        }

        if ($idConsulta <= 0 || empty($motivo)) {
            code(400);
            $this->jsonResponse("error", "ID de consulta y motivo son obligatorios");
        }

        $modeloConsulta = new Consulta();
        $resultado = $modeloConsulta->actualizarConsulta(
            $idConsulta, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento
        );

        if ($resultado === true) {
            code(200);
            $this->jsonResponse("ok", "La consulta fue actualizada exitosamente");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo actualizar la consulta");
        }
    }

    public function generarReporteMorbilidad() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("generar_reportes")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para generar reportes");
        }

        $data = $this->getRequestData();
        $fechaInicio = cleanValue($data, 'fecha_inicio') ?? ($_GET['fecha_inicio'] ?? '');
        $fechaFin    = cleanValue($data, 'fecha_fin') ?? ($_GET['fecha_fin'] ?? '');

        if (empty($fechaInicio) || empty($fechaFin)) {
            code(400);
            $this->jsonResponse("error", "Debe especificar la fecha de inicio y la fecha de fin.");
        }

        if (strtotime($fechaInicio) > strtotime($fechaFin)) {
            code(400);
            $this->jsonResponse("error", "La fecha de inicio no puede ser posterior a la fecha de fin.");
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json') || isset($_GET['json'])) {
            $modeloConsulta = new Consulta();
            $datos = $modeloConsulta->obtenerDatosReporteMorbilidad($fechaInicio, $fechaFin);
            code(200);
            $this->jsonResponse("ok", "Datos de reporte de morbilidad", $datos);
        }

        $_POST['fecha_inicio'] = $fechaInicio;
        $_POST['fecha_fin'] = $fechaFin;
        
        // Nota: Si necesitas renderizar una vista tradicional para el reporte, 
        // puedes invocar al ViewController correspondiente aquí en lugar de llamarte a ti mismo.
        exit();
    }

    public function buscarPatologia() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarPatologia($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function buscarPaciente() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarUsuario($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function obtenerConsultasPaciente($cedula = null) {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $ced = $cedula ?? (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');
        if (empty($ced)) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->obtenerConsultasPorPaciente($ced);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function buscarCondicion() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarCondicion($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function obtenerCondicionesPaciente($cedula = null) {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $ced = $cedula ?? (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');
        if (empty($ced)) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->obtenerCondicionesPaciente($ced);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function guardarRolesPermisos() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data = $this->getRequestData();
        $postedPermisos = $data['permisos'] ?? [];

        $userModel = new Usuario();
        $roles = $userModel->obtenerRoles();

        foreach ($roles as $role) {
            $idRol = $role['id_rol'];
            $permisosIds = isset($postedPermisos[$idRol]) ? $postedPermisos[$idRol] : [];
            $userModel->actualizarPermisosRol($idRol, $permisosIds);
        }

        code(200);
        $this->jsonResponse("ok", "¡Roles y permisos actualizados con éxito!");
    }

    public function registrarRol() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar roles y permisos");
        }

        $data = $this->getRequestData();
        $nombreRol = cleanValue($data, 'nombre_rol');
        $descripcionRol = cleanValue($data, 'descripcion_rol') ?? '';

        if (empty($nombreRol)) {
            code(400);
            $this->jsonResponse("error", "El nombre del rol es obligatorio");
        }

        $userModel = new Usuario();
        $resultado = $userModel->crearRol($nombreRol, $descripcionRol);

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

        $data = $this->getRequestData();
        $idRol = isset($data['id_rol']) ? (int)$data['id_rol'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $nombreRol = cleanValue($data, 'nombre_rol');
        $descripcionRol = cleanValue($data, 'descripcion_rol') ?? '';

        if ($idRol <= 0 || empty($nombreRol)) {
            code(400);
            $this->jsonResponse("error", "Datos del rol inválidos");
        }

        $userModel = new Usuario();
        $resultado = $userModel->actualizarRol($idRol, $nombreRol, $descripcionRol);

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

        $data = $this->getRequestData();
        $idRol = isset($data['id_rol']) ? (int)$data['id_rol'] : (isset($data['id']) ? (int)$data['id'] : 0);

        if ($idRol <= 0) {
            code(400);
            $this->jsonResponse("error", "No se puede eliminar este rol");
        }

        $userModel = new Usuario();
        $resultado = $userModel->eliminarRol($idRol);

        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Rol eliminado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Hubo un error al intentar eliminar el rol");
        }
    }

    public function guardarConfiguracion() {
        if (!$this->checkPerm("gestionar_roles_permisos")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la configuración");
        }

        $data = $this->getRequestData();
        $rolDefecto = isset($data['rol_defecto']) ? (int)$data['rol_defecto'] : 0;

        if ($rolDefecto <= 0) {
            code(400);
            $this->jsonResponse("error", "Seleccione un rol válido");
        }

        $userModel = new Usuario();
        $resultado = $userModel->actualizarRolDefecto($rolDefecto);

        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Configuración general guardada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo guardar la configuración");
        }
    }

    public function registrarCondicion() {
        if (!$this->checkPerm("gestionar_condiciones")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para gestionar condiciones");
        }

        $data = $this->getRequestData();
        $nombre = cleanValue($data, 'nombre_condicion');
        $descripcion = cleanValue($data, 'descripcion_condicion') ?? '';

        if (empty($nombre)) {
            code(400);
            $this->jsonResponse("error", "El nombre de la condición es obligatorio");
        }

        $model = new Condicion();
        $registrado = $model->registrarCondicion($nombre, $descripcion);

        if ($registrado) {
            code(201);
            $this->jsonResponse("ok", "¡Condición registrada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Hubo un error al registrar la condición");
        }
    }

    public function actualizarCondicion() {
        if (!$this->checkPerm("gestionar_condiciones")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para gestionar condiciones");
        }

        $data = $this->getRequestData();
        $id = isset($data['id']) ? (int)$data['id'] : (isset($data['id_condicion']) ? (int)$data['id_condicion'] : 0);
        $nombre = cleanValue($data, 'nombre_condicion');
        $descripcion = cleanValue($data, 'descripcion_condicion') ?? '';

        if ($id <= 0 || empty($nombre)) {
            code(400);
            $this->jsonResponse("error", "Datos de condición inválidos");
        }

        $model = new Condicion();
        $actualizado = $model->actualizarCondicion($id, $nombre, $descripcion);

        if ($actualizado) {
            code(200);
            $this->jsonResponse("ok", "¡Condición actualizada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo actualizar la condición");
        }
    }

    public function eliminarCondicion() {
        if (!$this->checkPerm("gestionar_condiciones")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para gestionar condiciones");
        }

        $data = $this->getRequestData();
        $id = isset($data['id']) ? (int)$data['id'] : (isset($data['id_condicion']) ? (int)$data['id_condicion'] : 0);

        if ($id <= 0) {
            code(400);
            $this->jsonResponse("error", "ID de condición no válido");
        }

        $model = new Condicion();
        $eliminado = $model->eliminarCondicion($id);

        if ($eliminado) {
            code(200);
            $this->jsonResponse("ok", "¡Condición eliminada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Error al intentar eliminar la condición");
        }
    }
}