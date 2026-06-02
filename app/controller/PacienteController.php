<?php
namespace app\controller;

use app\model\Paciente;
use Exception;

class PacienteController {
    private $db;

    public function __construct($conexion){
        $this->db = $conexion;
    }
    
    public function Registrar() {
        try {
            $cedula           = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
            $nombre           = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $apellido         = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
            $tipo             = isset($_POST['tipo']) ? $_POST['tipo'] : '';
            $pnf              = isset($_POST['pnf']) ? $_POST['pnf'] : '';
            $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
            $tlfprincipal     = isset($_POST['tlfprincipal']) ? trim($_POST['tlfprincipal']) : '';
            $tlfemergencia    = isset($_POST['tlfemergencia']) ? trim($_POST['tlfemergencia']) : '';
            $sexo             = isset($_POST['sexo']) ? $_POST['sexo'] : '';

            $modeloPaciente = new Paciente($this->db);
            
            $resultado = $modeloPaciente->registrarPaciente(
            $cedula, $nombre, $apellido, $tipo, $pnf, $fecha_nacimiento, $tlfprincipal, $tlfemergencia, $sexo
            );

            if ($resultado === true) {
                unset($_SESSION['inputs']);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Paciente registrado de manera exitosa!";
                header("Location: perfil");
                exit();
            } else {
                throw new Exception($resultado);
            }

        } catch (Exception $e) {
            $_SESSION['inputs'] = $_POST;
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "¡Paciente registrado de manera exitosa!";
            exit();
        }
    }
}
?>