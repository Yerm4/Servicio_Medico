<?php

namespace app\model;

use PDO;
use PDOException;

class NucleoPNF {
    private $pdo;

    public function __construct($conexion) {
        $this->pdo = $conexion;
    }

    // =========================================================================
    // VALIDACIONES DE EXISTENCIA / DUPLICADOS
    // =========================================================================

    private function existeNucleo($nombre) {
        $sql = "SELECT id_nucleo, estado FROM lista_nucleos
                WHERE REPLACE(LOWER(nombre_nucleo), ' ', '') = REPLACE(LOWER(:nombre), ' ', '')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nombre' => trim($nombre)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function existePNF($nombre) {
        $sql = "SELECT id_pnf, estado FROM lista_pnfs 
                WHERE REPLACE(LOWER(nombre_pnf), ' ', '') = REPLACE(LOWER(:nombre), ' ', '')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nombre' => trim($nombre)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si un nombre de núcleo ya lo tiene OTRO registro diferente (para actualización)
     */
    private function existeNucleoParaActualizar($nombre, $id_actual) {
        // Corregido: Agregada la limpieza de espacios REPLACE y la cláusula id_nucleo <> :id
        $sql = "SELECT COUNT(*) FROM lista_nucleos
                WHERE REPLACE(LOWER(nombre_nucleo), ' ', '') = REPLACE(LOWER(:nombre), ' ', '') 
                AND id_nucleo <> :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => trim($nombre), 
            ':id'      => $id_actual
        ]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si un nombre de PNF ya lo tiene OTRO registro diferente (para actualización)
     */
    private function existePnfParaActualizar($nombre, $id_actual) {
        // Corregido: Agregada la limpieza de espacios REPLACE y la cláusula id_pnf <> :id
        $sql = "SELECT COUNT(*) FROM lista_pnfs 
                WHERE REPLACE(LOWER(nombre_pnf), ' ', '') = REPLACE(LOWER(:nombre), ' ', '') 
                AND id_pnf <> :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => trim($nombre), 
            ':id'      => $id_actual
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // =========================================================================
    // MÉTODOS PARA GESTIÓN DE NÚCLEOS
    // =========================================================================

    public function obtenerNucleos() {
        try {
            $sql = "SELECT id_nucleo, nombre_nucleo FROM lista_nucleos WHERE estado = 1 ORDER BY nombre_nucleo ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerNucleos: " . $e->getMessage());
            return [];
        }
    }

    public function registrarNucleo($nombre) {
        $existe = $this->existeNucleo($nombre);

        if ($existe) {
            if ($existe['estado'] == 0) {
                $sql = "UPDATE lista_nucleos SET estado = 1 WHERE id_nucleo = :id";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([':id' => $existe['id_nucleo']]);
            }
            return "duplicado"; 
        }

        $sql = "INSERT INTO lista_nucleos (nombre_nucleo) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => trim($nombre)]);
    }

    public function actualizarNucleo($id, $nombre) {
        // Validar con el nuevo método blindado que no interfiera con otros núcleos existentes
        if ($this->existeNucleoParaActualizar($nombre, $id)) {
            return "duplicado";
        }

        $sql = "UPDATE lista_nucleos SET nombre_nucleo = :nombre WHERE id_nucleo = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => trim($nombre), ':id' => $id]);
    }

    public function desactivarNucleo($id) {
        $sql = "UPDATE lista_nucleos SET estado = 0 WHERE id_nucleo = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // =========================================================================
    // MÉTODOS PARA GESTIÓN DE PNFS
    // =========================================================================

    public function obtenerPNFS() {
        try {
            $sql = "SELECT id_pnf, nombre_pnf FROM lista_pnfs WHERE estado = 1 ORDER BY nombre_pnf ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPNFS: " . $e->getMessage());
            return [];
        }
    }

    public function registrarPNF($nombre) {
        $existe = $this->existePNF($nombre);

        if ($existe) {
            if ($existe['estado'] == 0) {
                $sql = "UPDATE lista_pnfs SET estado = 1 WHERE id_pnf = :id";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([':id' => $existe['id_pnf']]);
            }
            return "duplicado";
        }

        $sql = "INSERT INTO lista_pnfs (nombre_pnf) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => trim($nombre)]);
    }

    public function actualizarPNF($id, $nombre) {
        // Validar con el nuevo método blindado que no interfiera con otros PNFs existentes
        if ($this->existePnfParaActualizar($nombre, $id)) {
            return "duplicado";
        }

        $sql = "UPDATE lista_pnfs SET nombre_pnf = :nombre WHERE id_pnf = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':nombre' => trim($nombre), ':id' => $id]);
    }

    public function desactivarPNF($id) {
        $sql = "UPDATE lista_pnfs SET estado = 0 WHERE id_pnf = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // =========================================================================
    // MÉTODOS PARA VINCULACIÓN (OFERTAS ACADÉMICAS)
    // =========================================================================

    public function obtenerOfertasActivas() {
        try {
            $sql = "SELECT o.id_nucleo, o.id_pnf, n.nombre_nucleo, p.nombre_pnf 
                    FROM nucleo_pnf o
                    INNER JOIN lista_nucleos n ON o.id_nucleo = n.id_nucleo
                    INNER JOIN lista_pnfs p ON o.id_pnf = p.id_pnf
                    WHERE o.estado = 1 AND n.estado = 1 AND p.estado = 1
                    ORDER BY n.nombre_nucleo ASC, p.nombre_pnf ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerOfertasActivas: " . $e->getMessage());
            return [];
        }
    }

    public function registrarOferta($id_nucleo, $id_pnf) {
        $sqlCheck = "SELECT estado FROM nucleo_pnf WHERE id_nucleo = :id_nucleo AND id_pnf = :id_pnf";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([':id_nucleo' => $id_nucleo, ':id_pnf' => $id_pnf]);
        $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            if ($existe['estado'] == 0) {
                $sqlAnclar = "UPDATE nucleo_pnf SET estado = 1 WHERE id_nucleo = :id_nucleo AND id_pnf = :id_pnf";
                $stmtAnclar = $this->pdo->prepare($sqlAnclar);
                return $stmtAnclar->execute([':id_nucleo' => $id_nucleo, ':id_pnf' => $id_pnf]);
            }
            return "duplicado"; 
        }

        $sql = "INSERT INTO nucleo_pnf (id_nucleo, id_pnf) VALUES (:id_nucleo, :id_pnf)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_nucleo' => $id_nucleo, ':id_pnf' => $id_pnf]);
    }

    public function desactivarOferta($id_nucleo, $id_pnf) {
        $sql = "UPDATE nucleo_pnf SET estado = 0 WHERE id_nucleo = :id_nucleo AND id_pnf = :id_pnf";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_nucleo' => $id_nucleo, ':id_pnf' => $id_pnf]);
    }
    public function obtenerPnfsPorNucleo($id_nucleo) {
        try {
            $sql = "SELECT p.id_pnf, p.nombre_pnf 
                    FROM nucleo_pnf o
                    INNER JOIN lista_pnfs p ON o.id_pnf = p.id_pnf
                    WHERE o.id_nucleo = :id_nucleo AND o.estado = 1 AND p.estado = 1
                    ORDER BY p.nombre_pnf ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_nucleo' => $id_nucleo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPnfsPorNucleo: " . $e->getMessage());
            return [];
        }
    }
}
?>