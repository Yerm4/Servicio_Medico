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

    public function consultarUsuarios() {
        try {
            $sql = "SELECT * FROM usuarios WHERE activo = 1 ORDER BY fecha_creacion DESC LIMIT 19";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        catch (PDOException $e) {

        }
    }

    public function eliminarUsuario($cedula) {
    try {
        
        $sql = "UPDATE usuarios SET activo = 0 WHERE cedula = :cedula";

        $stmt = $this->pdo->prepare($sql);
        
        $resultado = $stmt->execute([
            "cedula" => $cedula
        ]);
        
        return $resultado; 

        } 
        catch (PDOException $e) {
            return false;
        }
    }

    public function buscarUsuarios($query) {
        try {
        
            $sql = "SELECT * FROM usuarios 
                    WHERE (cedula LIKE :query 
                    OR nombre LIKE :query 
                    OR apellido LIKE :query)
                    AND activo = 1
                    LIMIT 10"; 
                    
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                'query' => '%' . $query . '%'
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }

public function actualizarUsuarioCompleto($cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, $tlfprincipal, $nombre_contacto_emergencia, $tlfemergencia, $sexo, $direccion = '') {
    try {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, 
                    apellido = :apellido, 
                    tipo = :tipo, 
                    fecha_nacimiento = :fecha_nacimiento, 
                    tlfprincipal = :tlfprincipal, 
                    nombre_contacto_emergencia = :nombre_contacto_emergencia, 
                    tlfemergencia = :tlfemergencia, 
                    sexo = :sexo,
                    direccion = :direccion
                WHERE cedula = :cedula";
                
        $stmt = $this->pdo->prepare($sql);
        
        $resultado = $stmt->execute([
            'nombre'                      => $nombre,
            'apellido'                    => $apellido,
            'tipo'                        => $tipo,
            'fecha_nacimiento'            => $fecha_nacimiento,
            'tlfprincipal'                => $tlfprincipal,
            'nombre_contacto_emergencia'  => $nombre_contacto_emergencia,
            'tlfemergencia'               => $tlfemergencia,
            'sexo'                        => $sexo,
            'direccion'                   => $direccion,
            'cedula'                      => (int)$cedula
        ]);
        
        return $resultado;
        
    } catch (PDOException $e) {
        // Puedes descomentar la siguiente línea si necesitas debuggear fallos de tipos de datos en la DB:
        // echo $e->getMessage();
        return false;
    }

}
}