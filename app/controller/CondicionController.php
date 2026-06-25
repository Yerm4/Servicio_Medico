<?php
namespace app\controller;

use app\model\Condicion;
use PDOException;

class CondicionController {

    private $pdo;
    
    public function __construct($conexion){
        $this->pdo = $conexion;
    }

    public function registrar() {
        $nombre = isset($_POST['nombre_condicion']) ? trim($_POST['nombre_condicion']) : '';
        $descripcion = isset($_POST['descripcion_condicion']) ? trim($_POST['descripcion_condicion']) : '';

        if (!empty($nombre)) {
            $model = new Condicion($this->pdo);
            $registrado = $model->registrarCondicion($nombre, $descripcion);

            if ($registrado) {
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Condición registrada con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "Hubo un error al registrar la condición.";
            }
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "El nombre de la condición es obligatorio.";
        }
        
        header("Location: configuracion");
        exit();
    }

    public function actualizar() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = isset($_POST['nombre_condicion']) ? trim($_POST['nombre_condicion']) : '';
        $descripcion = isset($_POST['descripcion_condicion']) ? trim($_POST['descripcion_condicion']) : '';

        if ($id > 0 && !empty($nombre)) {
            $model = new Condicion($this->pdo);
            $actualizado = $model->actualizarCondicion($id, $nombre, $descripcion);

            if ($actualizado) {
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Condición actualizada con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "No se pudo actualizar o no se realizaron cambios.";
            }
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "Datos inválidos.";
        }

        header("Location: configuracion");
        exit();
    }

    public function eliminar() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id > 0) {
            $model = new Condicion($this->pdo);
            $eliminado = $model->eliminarCondicion($id);

            if ($eliminado) {
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Condición eliminada con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "Error al intentar eliminar la condición.";
            }
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "ID de condición no válido.";
        }
        
        header("Location: configuracion");
        exit();
    }
}
