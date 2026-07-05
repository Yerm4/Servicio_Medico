<?php
namespace app\controller;

use app\model\Consulta;
use Exception;

class ConsultaController {
    private $db;

    public function __construct($conexion){
        $this->db = $conexion;
    }

    public function registrar() {
        try {
            $cedulaPaciente = isset($_POST['cedula_paciente']) ? trim($_POST['cedula_paciente']) : '';
            $motivo         = isset($_POST['motivo_de_visita']) ? trim($_POST['motivo_de_visita']) : '';
            $observaciones  = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
            $medicamento    = isset($_POST['medicamento_suministrado']) ? trim($_POST['medicamento_suministrado']) : '';
            
            // Symptoms
            $sintomas       = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];
            if (!is_array($sintomas)) {
                $sintomas = array_filter(array_map('trim', explode(',', $sintomas)));
            }

            // Diagnoses (ICD codes)
            $diagnosticos   = isset($_POST['diagnosticos']) ? $_POST['diagnosticos'] : [];
            if (!is_array($diagnosticos)) {
                $diagnosticos = array_filter(array_map('trim', explode(',', $diagnosticos)));
            }

            $condiciones    = isset($_POST['condiciones']) ? $_POST['condiciones'] : [];
            if (!is_array($condiciones)) {
                $condiciones = array_filter(array_map('intval', explode(',', $condiciones)));
            }

            $cedulaMedico = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';

            if (empty($cedulaPaciente) || empty($motivo) || empty($cedulaMedico)) {
                throw new Exception("La consulta no fue registrada, revisa que todos los campos estén llenos");
            }

            $modeloConsulta = new Consulta($this->db);
            $resultado = $modeloConsulta->registrarConsulta($cedulaPaciente, $cedulaMedico, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento);

            if ($resultado === true) {
                unset($_SESSION['inputs']);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "La consulta fue registrada exitosamente";
                header("Location: perfil");
                exit();
            } else {
                throw new Exception("La consulta no fue registrada, revisa que todos los campos estén llenos");
            }
        } catch (Exception $e) {
            $_SESSION['inputs'] = $_POST;
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = $e->getMessage();
            header("Location: perfil");
            exit();
        }
    }

    public function buscarPatologiaAjax() {
        header('Content-Type: application/json');
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $resultados = $modeloConsulta->buscarPatologia($q);
        echo json_encode($resultados);
        exit();
    }

    public function buscarPacienteAjax() {
        header('Content-Type: application/json');
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $resultados = $modeloConsulta->buscarUsuario($q);
        echo json_encode($resultados);
        exit();
    }

    public function actualizar() {
        try {
            $idConsulta    = isset($_POST['id_consulta']) ? (int)$_POST['id_consulta'] : 0;
            $motivo        = isset($_POST['motivo_de_visita']) ? trim($_POST['motivo_de_visita']) : '';
            $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
            $medicamento   = isset($_POST['medicamento_suministrado']) ? trim($_POST['medicamento_suministrado']) : '';

            $sintomas      = isset($_POST['sintomas']) ? $_POST['sintomas'] : [];
            if (!is_array($sintomas)) {
                $sintomas = array_filter(array_map('trim', explode(',', $sintomas)));
            }

            $diagnosticos  = isset($_POST['diagnosticos']) ? $_POST['diagnosticos'] : [];
            if (!is_array($diagnosticos)) {
                $diagnosticos = array_filter(array_map('trim', explode(',', $diagnosticos)));
            }

            $condiciones   = isset($_POST['condiciones']) ? $_POST['condiciones'] : [];
            if (!is_array($condiciones)) {
                $condiciones = array_filter(array_map('intval', explode(',', $condiciones)));
            }

            if ($idConsulta <= 0 || empty($motivo)) {
                throw new Exception("La consulta no fue actualizada, revisa que todos los campos estén llenos");
            }

            $modeloConsulta = new Consulta($this->db);
            $resultado = $modeloConsulta->actualizarConsulta($idConsulta, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento);

            if ($resultado === true) {
                unset($_SESSION['inputs']);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "La consulta fue actualizada exitosamente";
                header("Location: perfil");
                exit();
            } else {
                throw new Exception("La consulta no fue actualizada, revisa que todos los campos estén llenos");
            }
        } catch (Exception $e) {
            $_SESSION['inputs'] = $_POST;
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = $e->getMessage();
            header("Location: perfil");
            exit();
        }
    }

    public function obtenerConsultasPacienteAjax() {
        header('Content-Type: application/json');
        $cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';
        if (empty($cedula)) {
            echo json_encode([]);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $resultados = $modeloConsulta->obtenerConsultasPorPaciente($cedula);
        echo json_encode($resultados);
        exit();
    }

    public function buscarCondicionAjax() {
        header('Content-Type: application/json');
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $resultados = $modeloConsulta->buscarCondicion($q);
        echo json_encode($resultados);
        exit();
    }

    public function obtenerCondicionesPacienteAjax() {
        header('Content-Type: application/json');
        $cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';
        if (empty($cedula)) {
            echo json_encode([]);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $resultados = $modeloConsulta->obtenerCondicionesPaciente($cedula);
        echo json_encode($resultados);
        exit();
    }

    public function buscarConsultasAjax() {
        header('Content-Type: application/json');
        $query = isset($_POST['query']) ? trim($_POST['query']) : '';
        $modeloConsulta = new Consulta($this->db);
        if (empty($query)) {
            $resultados = $modeloConsulta->obtenerConsultasRecientes(10);
        } else {
            $resultados = $modeloConsulta->buscarConsultas($query, 20);
        }
        echo json_encode($resultados);
        exit();
    }

    public function obtenerConsultaPorIdAjax() {
        header('Content-Type: application/json');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id <= 0) {
            echo json_encode(['error' => 'ID de consulta inválido.']);
            exit();
        }
        $modeloConsulta = new Consulta($this->db);
        $consulta = $modeloConsulta->obtenerConsultaPorId($id);
        if ($consulta) {
            echo json_encode($consulta);
        } else {
            echo json_encode(['error' => 'No se encontró la consulta médica.']);
        }
        exit();
    }
}
