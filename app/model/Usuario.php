<?php
namespace app\model;

use PDO;
use PDOException;

class Usuario {

    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    public function registrarUsuario($cedula, $password) {
        
        try {
            $sql = "INSERT INTO usuarios (contrasena, cedula) VALUES (:password, :cedula)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
            "password" => $password,
            "cedula" => $cedula
            ]);
            return true;
        }

        catch (PDOException $e) {
            echo $e;
            return false;
        }

    }

    public function loginUsuario($cedula) {
        try {
            $sql = "SELECT * FROM usuarios WHERE cedula = :cedula";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                "cedula" => $cedula
            ]);
                        
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        catch (PDOException $e) {
            echo $e;
            return false;
        }
    }
}