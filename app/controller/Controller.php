<?php
namespace app\controller;

use app\model\Usuario;
use PDOException;

class Controller {

    private $pdo;
    public function __construct($conexion){
        $this->pdo = $conexion;
    }
    public function consultar () {
        $consulta = new Usuario($this->pdo);
        return $consulta->consultarUsuarios();
    }

    private function jsonResponse($status, $message = "", $data = null, $redirect = null) {
        header("Content-Type: application/json");
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "redirect" => $redirect
        ]);
        exit();
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

        $query = isset($_POST['query']) ? trim($_POST['query']) : '';

        $model = new Usuario($this->pdo);

        if (empty($query)) {
            $resultados = $model->consultarUsuarios();
        } else {
            $resultados = $model->buscarUsuarios($query);
        }

        echo json_encode($resultados);
        exit();
    }

    public function obtenerUsuarioPorCedula() {
        header('Content-Type: application/json');
        $cedula = isset($_POST['id']) ? trim($_POST['id']) : '';

        $model = new Usuario($this->pdo);
        $usuario = $model->login($cedula);

        if ($usuario) {
            echo json_encode($usuario);
        } else {
            echo json_encode(['error' => 'No se encontró el registro.']);
        }
        exit();
}

public function actualizar() {

    if (isset($_POST['form']) && $_POST['form'] === 'actualizar_usuario') {
        header('Content-Type: application/json');

        $cedula                     = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
        $nombre                     = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $apellido                   = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
        $tipo                       = isset($_POST['tipo']) ? (int)$_POST['tipo'] : 0;
        $fecha_nacimiento           = isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : null;
        $tlfprincipal               = isset($_POST['tlfprincipal']) ? trim($_POST['tlfprincipal']) : '';
        $nombre_contacto_emergencia = isset($_POST['nombre_contacto_emergencia']) ? trim($_POST['nombre_contacto_emergencia']) : '';
        $tlfemergencia              = isset($_POST['tlfemergencia']) ? trim($_POST['tlfemergencia']) : '';
        $direccion                  = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
        $sexo                       = isset($_POST['sexo']) ? (int)$_POST['sexo'] : 1;
        $nucleo_id                  = isset($_POST['nucleo_id']) && $_POST['nucleo_id'] !== '' ? (int)$_POST['nucleo_id'] : null;
        $pnf_id                     = isset($_POST['pnf_id']) && $_POST['pnf_id'] !== '' ? (int)$_POST['pnf_id'] : null;

        $rol = null;
        if (isset($_POST['rol']) && isset($_SESSION['cedula'])) {
            $model = new Usuario($this->pdo);
            if ($model->tienePermiso($_SESSION['cedula'], 'gestionar_roles_permisos')) {
                $rol = (int)$_POST['rol'];
            }
        }

        if (strlen($nombre) > 25) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre es muy largo.']);
            exit;
        }

        if (!empty($cedula)) {
            if (!isset($model)) {
                $model = new Usuario($this->pdo);
            }
            
            $guardado = $model->actualizarUsuarioCompleto(
                $cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, 
                $tlfprincipal, $nombre_contacto_emergencia, $tlfemergencia, $sexo, $direccion, $rol, $nucleo_id, $pnf_id
            );

            if ($guardado) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se realizaron cambios o hubo un fallo en la base de datos.']);
            }
        } 
        else {
            echo json_encode(['status' => 'error', 'message' => 'Cédula inválida.']);
        }
        exit();
        }
    }

    public function buscarPnfs() {
        if (isset($_POST['form']) && $_POST['form'] === 'buscar_pnfs') {
            header('Content-Type: application/json');
            $model = new Usuario($this->pdo);
            $pnfs = $model->buscarPnfs();

            if ($pnfs === false) {
                echo json_encode([]);
            }

            else {
                echo json_encode($pnfs, JSON_UNESCAPED_UNICODE);
            }
            
            exit();
        }
    }
}