<?php

namespace app\controller;

use app\model\NucleoPNF;
use app\controller\BaseController;

class NucleoPnfController extends BaseController {

    public function obtenerPnfsPorNucleo($id) {
        if ((int)$id <= 0) {
            code(400);
            $this->jsonResponse("error", "La id no es valida");
        }
        $pnfModel = new NucleoPNF();
        $pnfs = $pnfModel->obtenerPnfsPorNucleo((int)$id);
        code(200);
        $this->jsonResponse("ok", "Pnfs enviados", $pnfs ?: []);
    }

    public function registrarNucleo() {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }

        $data = $this->getRequestData();
        $nombre = cleanValue($data, 'nombre_nucleo');

        if (empty($nombre)) {
            code(400);
            $this->jsonResponse("error", "El nombre del núcleo es obligatorio");
        }

        if (strlen($nombre) < 4 || strlen($nombre) > 50) {
            code(400);
            $this->jsonResponse("error", "El nombre del núcleo debe tener entre 4 y 50 caracteres");
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
            code(400);
            $this->jsonResponse("error", "El nombre del núcleo solo puede contener letras y espacios");
        }

        $model = new NucleoPNF();
        $resultado = $model->registrarNucleo($nombre);

        if ($resultado["message"] === "duplicado") {
            code(400);
            $this->jsonResponse("error", "El núcleo ya se encuentra registrado");
        } elseif ($resultado) {
            code(201);
            $this->jsonResponse("ok", "¡Núcleo registrado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar registrar el núcleo");
        }
    }

    public function actualizarNucleo() {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }

        $data = $this->getRequestData();
        $id = isset($data['id_nucleo']) ? (int)$data['id_nucleo'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $nombre = cleanValue($data, 'nombre_nucleo');

        if ($id <= 0 || empty($nombre)) {
            code(400);
            $this->jsonResponse('error', 'El campo no puede estar vacio.');
        }

        if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            code(400);
            $this->jsonResponse('error', 'El nombre del núcleo debe tener entre 4 y 100 caracteres.');
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
            code(400);
            $this->jsonResponse('error', 'El nombre del núcleo no puede contener números ni caracteres especiales.');
        }

        $NucleoModelo = new NucleoPNF();
        $resultado = $NucleoModelo->actualizarNucleo($id, $nombre);

        if ($resultado["message"] === "duplicado") {
            code(400);
            $this->jsonResponse('error', 'El nucleo ya se encuentra registrado.');
        } elseif ($resultado) {
            code(200);
            $this->jsonResponse('ok', '¡Núcleo actualizado con éxito!');
        } else {
            code(400);
            $this->jsonResponse('error', 'Ocurrió un error al intentar actualizar el núcleo.');
        }
    }

    public function eliminarNucleo($id) {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }
        $id = (int)$id;

        if ($id <= 0) {
            code(400);
            $this->jsonResponse("error", "ID de núcleo inválido");
        }

        $model = new NucleoPNF();
        if ($model->desactivarNucleo($id)) {
            code(200);
            $this->jsonResponse("ok", "¡Núcleo eliminado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar eliminar el núcleo");
        }
    }

    public function registrarPnf() {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }

        $data = $this->getRequestData();
        $nombre = cleanValue($data, 'nombre_pnf');

        if (empty($nombre)) {
            code(400);
            $this->jsonResponse("error", "El campo no puede estar vacio");
        }

        if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            code(400);
            $this->jsonResponse("error", "El nombre del PNF debe tener entre 4 y 100 caracteres");
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
            code(400);
            $this->jsonResponse("error", "El nombre del PNF solo puede contener letras y espacios");
        }

        $model = new NucleoPNF();
        $resultado = $model->registrarPnf($nombre);

        if ($resultado['message'] === "duplicado") {
            code(400);
            $this->jsonResponse("error", "El PNF ya se encuentra registrado");
        } elseif ($resultado) {
            code(201);
            $this->jsonResponse("ok", "¡PNF registrado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar registrar el PNF");
        }
    }

    public function actualizarPnf() {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }

        $data = $this->getRequestData();
        $id = isset($data['id_pnf']) ? (int)$data['id_pnf'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $nombre = cleanValue($data, 'nombre_pnf');

        if ($id <= 0 || empty($nombre)) {
            code(400);
            $this->jsonResponse("error", "El campo no puede estar vacio");
        }

        if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            code(400);
            $this->jsonResponse("error", "El nombre del PNF debe tener entre 4 y 100 caracteres");
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
            code(400);
            $this->jsonResponse("error", "El nombre del PNF no puede contener números ni caracteres especiales");
        }

        $model = new NucleoPNF();
        $resultado = $model->actualizarPnf($id, $nombre);

        if ($resultado === "duplicado") {
            code(400);
            $this->jsonResponse("error", "El PNF ya se encuentra registrado");
        } elseif ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡PNF actualizado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar actualizar el PNF");
        }
    }

    public function eliminarPnf($id) {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }
        $id = (int)$id;
        if ($id <= 0) {
            code(400);
            $this->jsonResponse("error", "ID de PNF inválido");
        }

        $model = new NucleoPNF();
        if ($model->desactivarPnf($id)) {
            code(200);
            $this->jsonResponse("ok", "¡PNF eliminado con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar eliminar el PNF");
        }
    }

    public function registrarOferta() {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }
    
        $data = $this->getRequestData();
        $nucleoId = isset($data['nucleo_id']) ? (int)$data['nucleo_id'] : (isset($data['id_nucleo']) ? (int)$data['id_nucleo'] : 0);
        $pnfId    = isset($data['pnf_id']) ? (int)$data['pnf_id'] : (isset($data['id_pnf']) ? (int)$data['id_pnf'] : 0);
    
        if ($nucleoId <= 0 || $pnfId <= 0) {
            code(400);
            $this->jsonResponse("error", "Debe seleccionar un núcleo y una carrera válidos");
        }
    
        $model = new NucleoPNF();
        $resultado = $model->registrarOferta($nucleoId, $pnfId);
    
        if ($resultado === "duplicado") {
            code(400);
            $this->jsonResponse("error", "Esta carrera ya está ofertada en la sede seleccionada");
        } elseif ($resultado) {
            code(201);
            $this->jsonResponse("ok", "¡Oferta académica asignada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al asignar la oferta");
        }
    }
    
    public function eliminarOferta($id = null) {
        if (!$this->checkPerm("gestionar_oferta_academica")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para administrar la oferta académica");
        }
    
        $data = $this->getRequestData();
        $nucleoId = isset($data['id_nucleo']) ? (int)$data['id_nucleo'] : 0;
        $pnfId    = isset($data['id_pnf']) ? (int)$data['id_pnf'] : 0;
    
        $model = new NucleoPNF();
        $resultado = false;

        if ($nucleoId > 0 && $pnfId > 0) {
            $resultado = $model->desactivarOferta($nucleoId, $pnfId);
        } else {
            code(400);
            $this->jsonResponse("error", "Datos insuficientes para eliminar la oferta");
        }
    
        if ($resultado) {
            code(200);
            $this->jsonResponse("ok", "¡Oferta académica eliminada con éxito!");
        } else {
            code(400);
            $this->jsonResponse("error", "Ocurrió un error al intentar eliminar la oferta");
        }
    }
}