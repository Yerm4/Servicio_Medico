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

        public function registrarPaciente($cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, $tlfprincipal, $tlfemergencia, $nombre_contacto_emergencia, $sexo) {
        
            try {

            $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE cedula = :cedula";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute(
            [':cedula' => $cedula]
        );

        if ($stmtCheck->fetchColumn() > 0){
            return "La cédula de identidad ya se encuentra registrada en el sistema.";
        }

        $contraseñaCreada = $cedula.'uptaeb';
        $contraseñaEncriptada = password_hash($contraseñaCreada, PASSWORD_ARGON2I);

        $sql = "INSERT INTO usuarios (cedula, nombre, apellido, contrasena, tipo, fecha_nacimiento, tlfprincipal, tlfemergencia, nombre_contacto_emergencia, sexo) 
                VALUES (:cedula, :nombre, :apellido, :contrasena, :tipo, :fecha_nacimiento, :tlfprincipal, :tlfemergencia, :nombre_contacto_emergencia, :sexo)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':cedula'           => (int)$cedula, 
            ':nombre'           => $nombre,
            ':apellido'         => $apellido,
            ':contrasena'       => $contraseñaEncriptada,
            ':tipo'             => (int)$tipo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':tlfprincipal'     => $tlfprincipal,
            ':tlfemergencia'    => $tlfemergencia,
            ':nombre_contacto_emergencia' => $nombre_contacto_emergencia,
            ':sexo'             => (int)$sexo
        ]);

        return true;

        } catch (PDOException $error) {
            return "Error al registrar paciente: " . $error->getMessage();
        }
    }
}
