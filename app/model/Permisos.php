<?php
namespace app\model;

use app\model\BaseModel;
use PDO;

class Permisos extends BaseModel {
    public function id() {
        $stmtP = $this->pdo->prepare("SELECT id_permiso FROM lista_permisos WHERE nombre_permiso IN ('ver_consultas', 'realizar_consulta', 'modificar_consulta')");
        $stmtP->execute();
        return $stmtP->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteId($inClause) {
        $stmtDel = $this->pdo->prepare("DELETE FROM roles_permisos WHERE id_rol = 4 AND id_permiso IN ($inClause)");
        $stmtDel->execute();
    }

    public function asignarPermisoARol($roleId, $pName) {
        // 1. Buscar el ID del permiso mediante su nombre
        $stmtId = $this->pdo->prepare("SELECT id_permiso FROM lista_permisos WHERE nombre_permiso = :name");
        $stmtId->execute([':name' => trim($pName)]);
        $idPerm = $stmtId->fetchColumn();

        // Si el permiso no existe en la base de datos
        if (!$idPerm) {
            return ['status' => 'error', 'message' => 'El permiso especificado no existe'];
        }

        // 2. Verificar si el rol ya tiene asignado este permiso
        $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM roles_permisos WHERE id_rol = :role_id AND id_permiso = :id_perm");
        $stmtCheck->execute([
            ':role_id' => $roleId, 
            ':id_perm' => $idPerm
        ]);
        
        // Guardamos el resultado en una variable (aquí estaba el doble fetch original)
        $totalAsignaciones = $stmtCheck->fetchColumn();

        // 3. Si no está asignado, lo insertamos
        if ($totalAsignaciones == 0) {
            $stmtIns = $this->pdo->prepare("INSERT INTO roles_permisos (id_rol, id_permiso) VALUES (:role_id, :id_perm)");
            $stmtIns->execute([
                ':role_id' => $roleId, 
                ':id_perm' => $idPerm
            ]);
            
            return ['status' => 'ok', 'message' => 'Permiso asignado correctamente'];
        }

        return ['status' => 'info', 'message' => 'El rol ya cuenta con este permiso'];
    }
}