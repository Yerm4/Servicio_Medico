<?php
namespace app\model;

use PDO;
use PDOException;

class Condicion {

    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    public function consultarCondiciones() {
        try {
            $sql = "SELECT id, nombre_condicion, descripcion_condicion FROM lista_condiciones ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function registrarCondicion($nombre, $descripcion) {
        try {
            $sql = "INSERT INTO lista_condiciones (nombre_condicion, descripcion_condicion) VALUES (:nombre, :descripcion)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizarCondicion($id, $nombre, $descripcion) {
        try {
            $sql = "UPDATE lista_condiciones SET nombre_condicion = :nombre, descripcion_condicion = :descripcion WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':id' => (int)$id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarCondicion($id) {
        try {
            $this->pdo->beginTransaction();
            $sqlDelCu = "DELETE FROM condiciones_usuarios WHERE id_condicion = :id";
            $stmtCu = $this->pdo->prepare($sqlDelCu);
            $stmtCu->execute([':id' => (int)$id]);

            $sqlDelC = "DELETE FROM lista_condiciones WHERE id = :id";
            $stmtC = $this->pdo->prepare($sqlDelC);
            $stmtC->execute([':id' => (int)$id]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
