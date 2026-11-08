<?php

namespace app\controller;

use app\model\Condicion;
use app\controller\BaseController;

class CondicionController extends BaseController {

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