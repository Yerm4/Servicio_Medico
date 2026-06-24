<?php
namespace app\controller;

use app\model\Paciente;
use Exception;

class PacienteController {
    private $db;

    public function __construct($conexion){
        $this->db = $conexion;
    }
    
}
?>