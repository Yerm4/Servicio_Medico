<?php

namespace app\model;

use PDO;
use PDOException;

class Paciente
    {
        private $db;

        public function __construct($conexion) {
            $this->db = $conexion;
        }

}
