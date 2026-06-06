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
            $cedula            = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
            $nombre            = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $apellido          = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
            $tipo              = isset($_POST['tipo']) ? $_POST['tipo'] : '';
            $fecha_nacimiento  = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
            $tlfprincipal      = isset($_POST['tlfprincipal']) ? trim($_POST['tlfprincipal']) : '';
            $nombre_contacto_emergencia = isset($_POST['nombre_contacto_emergencia']) ? trim($_POST['nombre_contacto_emergencia']) : '';
            $tlfemergencia     = isset($_POST['tlfemergencia']) ? trim($_POST['tlfemergencia']) : '';
            $sexo              = isset($_POST['sexo']) ? $_POST['sexo'] : '';
            $nucleo_id         = isset($_POST['nucleo_id']) ? $_POST['nucleo_id'] : '';

            $modeloPaciente = new Paciente($this->db);
            
            $resultado = $modeloPaciente->registrarPaciente(
                $cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, 
                $tlfprincipal, $tlfemergencia, $nombre_contacto_emergencia, $sexo, 
                $nucleo_id
            );

            if ($resultado === true) {
                unset($_SESSION['inputs']); 
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Paciente registrado de manera exitosa!";
                
                header("Location: form"); 
                exit();
            } else {
                throw new Exception($resultado);
            }

        } catch (Exception $e) {
            $_SESSION['inputs'] = $_POST; 
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = $e->getMessage();
            
            header("Location: form"); 
            exit();
        }
    }
}
?>