<?php

namespace app\controller;

use app\model\NucleoPNF;

class NucleoPnfController {
    private $model;

    public function __construct($conexion) {
        $this->model = new NucleoPNF($conexion);
    }

   public function registrarNucleo() {
        header('Content-Type: application/json');
        $nombre = isset($_POST['nombre_nucleo']) ? trim($_POST['nombre_nucleo']) : '';

        if (empty($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del núcleo es obligatorio.']);
            exit();
        }

        if (strlen($nombre) < 4 || strlen($nombre) > 50) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del núcleo debe tener entre 4 y 50 caracteres.']);
            exit();
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del núcleo solo puede contener letras y espacios.']);
            exit();
        }

        $resultado = $this->model->registrarNucleo($nombre);

        if ($resultado === "duplicado") {
            echo json_encode(['status' => 'error', 'message' => 'El núcleo ya se encuentra registrado.']);
        } elseif ($resultado) {
            echo json_encode(['status' => 'success', 'message' => '¡Núcleo registrado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar registrar el núcleo.']);
        }
        exit();
    }
    public function actualizarNucleo() {
        header('Content-Type: application/json');
        $id = isset($_POST['id_nucleo']) ? (int)$_POST['id_nucleo'] : 0;
        $nombre = isset($_POST['nombre_nucleo']) ? trim($_POST['nombre_nucleo']) : '';

        if ($id <= 0 || empty($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El campo no puede estar vacio.']);
            exit();
        }

         if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del núcleo debe tener entre 4 y 100 caracteres.']);
            exit();
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$/u', $nombre)) {
        echo json_encode(['status' => 'error', 'message' => 'El nombre del núcleo no puede contener números ni caracteres especiales.']);
            exit();
        }

        $resultado = $this->model->actualizarNucleo($id, $nombre);

        if ($resultado === "duplicado") {
            echo json_encode(['status' => 'error', 'message' => 'El nucleo ya se encuentra registrado.']);
        } elseif ($resultado) {
            echo json_encode(['status' => 'success', 'message' => '¡Núcleo actualizado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar actualizar el núcleo.']);
        }
        exit();
    }

    public function eliminarNucleo() {
        header('Content-Type: application/json');
        $id = isset($_POST['id_nucleo']) ? (int)$_POST['id_nucleo'] : 0;


        if ($this->model->desactivarNucleo($id)) {
            echo json_encode(['status' => 'success', 'message' => '¡Núcleo eliminado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el núcleo.']);
        }
        exit();
    }

    // PNFS

    public function registrarPnf() {
        header('Content-Type: application/json');
        $nombre = isset($_POST['nombre_pnf']) ? trim($_POST['nombre_pnf']) : '';

        if (empty($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El campo no puede estar vacio.']);
            exit();
        }
           
        if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del PNF debe tener entre 4 y 100 caracteres.']);
            exit();
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del PNF no puede contener números ni caracteres especiales.']);
            exit();
        }


        $resultado = $this->model->registrarPNF($nombre);

        if ($resultado === "duplicado") {
            echo json_encode(['status' => 'error', 'message' => 'El PNF ya se encuentra registrado.']);
        } elseif ($resultado) {
            echo json_encode(['status' => 'success', 'message' => '¡PNF registrado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar registrar el PNF.']);
        }
        exit();
    }

    public function actualizarPnf() {
        header('Content-Type: application/json');
        $id = isset($_POST['id_pnf']) ? (int)$_POST['id_pnf'] : 0;
        $nombre = isset($_POST['nombre_pnf']) ? trim($_POST['nombre_pnf']) : '';

        if ($id <= 0 || empty($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes para actualizar el PNF.']);
            exit();
        }
          if (strlen($nombre) < 4 || strlen($nombre) > 100) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del PNF debe tener entre 4 y 100 caracteres.']);
            exit();
        }

        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/u', $nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del PNF no puede contener números ni caracteres especiales.']);
            exit();
        }


        $resultado = $this->model->actualizarPNF($id, $nombre);

        if ($resultado === "duplicado") {
            echo json_encode(['status' => 'error', 'message' => 'El PNF ya se encuentra registrado.']);
        } elseif ($resultado) {
            echo json_encode(['status' => 'success', 'message' => '¡PNF actualizado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar actualizar el PNF.']);
        }
        exit();
    }

    public function eliminarPnf() {
        header('Content-Type: application/json');
        $id = isset($_POST['id_pnf']) ? (int)$_POST['id_pnf'] : 0;

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Identificador de PNF inválido.']);
            exit();
        }

        if ($this->model->desactivarPNF($id)) {
            echo json_encode(['status' => 'success', 'message' => '¡PNF eliminado con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar el PNF.']);
        }
        exit();
    }

    // OFERTAS ACADÉMICAS

    public function registrarOferta() {
        header('Content-Type: application/json');
        $id_nucleo = isset($_POST['id_nucleo']) ? (int)$_POST['id_nucleo'] : 0;
        $id_pnf = isset($_POST['id_pnf']) ? (int)$_POST['id_pnf'] : 0;

        if ($id_nucleo <= 0 || $id_pnf <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar un Núcleo y un PNF.']);
            exit();
        }

        $resultado = $this->model->registrarOferta($id_nucleo, $id_pnf);

        if ($resultado === "duplicado") {
            echo json_encode(['status' => 'error', 'message' => 'Esta oferta académica ya se encuentra registrada.']);
        } elseif ($resultado) {
            echo json_encode(['status' => 'success', 'message' => '¡Oferta académica registrada con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al intentar registrar la oferta.']);
        }
        exit();
    }

    public function eliminarOferta() {
        header('Content-Type: application/json');
        $id_nucleo = isset($_POST['id_nucleo']) ? (int)$_POST['id_nucleo'] : 0;
        $id_pnf = isset($_POST['id_pnf']) ? (int)$_POST['id_pnf'] : 0;

        if ($id_nucleo <= 0 || $id_pnf <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos para desvincular la oferta.']);
            exit();
        }

        if ($this->model->desactivarOferta($id_nucleo, $id_pnf)) {
            echo json_encode(['status' => 'success', 'message' => '¡Oferta académica eliminada con éxito!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al eliminar la oferta.']);
        }
        exit();
    }
    public function obtenerPnfsPorNucleo() {
        header('Content-Type: application/json');
        $id_nucleo = isset($_POST['id_nucleo']) ? (int)$_POST['id_nucleo'] : 0;

        if ($id_nucleo <= 0) {
            echo json_encode([]);
            exit();
        }

        $pnfs = $this->model->obtenerPnfsPorNucleo($id_nucleo);
        echo json_encode($pnfs, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
