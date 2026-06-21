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
            $sql = "SELECT u.*, t.nombre_tipo, r.nombre_rol 
                    FROM usuarios u
                    LEFT JOIN lista_tipos t ON u.tipo = t.id_tipo
                    LEFT JOIN lista_roles r ON u.rol = r.id_rol
                    WHERE u.activo = 1 
                    ORDER BY u.fecha_creacion DESC 
                    LIMIT 19";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
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
        
            $sql = "SELECT u.*, t.nombre_tipo, r.nombre_rol 
                    FROM usuarios u
                    LEFT JOIN lista_tipos t ON u.tipo = t.id_tipo
                    LEFT JOIN lista_roles r ON u.rol = r.id_rol
                    WHERE (u.cedula LIKE :query 
                    OR u.nombre LIKE :query 
                    OR u.apellido LIKE :query)
                    AND u.activo = 1
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

public function actualizarUsuarioCompleto($cedula, $nombre, $apellido, $tipo, $fecha_nacimiento, $tlfprincipal, $nombre_contacto_emergencia, $tlfemergencia, $sexo, $direccion = '', $rol = null) {
    try {
        if ($rol !== null) {
            $sql = "UPDATE usuarios 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        tipo = :tipo, 
                        fecha_nacimiento = :fecha_nacimiento, 
                        tlfprincipal = :tlfprincipal, 
                        nombre_contacto_emergencia = :nombre_contacto_emergencia, 
                        tlfemergencia = :tlfemergencia, 
                        sexo = :sexo,
                        direccion = :direccion,
                        rol = :rol
                    WHERE cedula = :cedula";
            $stmt = $this->pdo->prepare($sql);
            $params = [
                'nombre'                      => $nombre,
                'apellido'                    => $apellido,
                'tipo'                        => $tipo,
                'fecha_nacimiento'            => $fecha_nacimiento,
                'tlfprincipal'                => $tlfprincipal,
                'nombre_contacto_emergencia'  => $nombre_contacto_emergencia,
                'tlfemergencia'               => $tlfemergencia,
                'sexo'                        => $sexo,
                'direccion'                   => $direccion,
                'rol'                         => $rol,
                'cedula'                      => (int)$cedula
            ];
        } else {
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
            $params = [
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
            ];
        }
        $resultado = $stmt->execute($params);
        return $resultado;
        
    } catch (PDOException $e) {
        return false;
    }

}

    public function tienePermiso($cedula, $permiso) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios u
                    INNER JOIN roles_permisos rp ON u.rol = rp.id_rol
                    INNER JOIN lista_permisos lp ON rp.id_permiso = lp.id_permiso
                    WHERE u.cedula = :cedula AND lp.nombre_permiso = :permiso";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'cedula' => (int)$cedula,
                'permiso' => $permiso
            ]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerRoles() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_rol, nombre_rol, descripcion_rol FROM lista_roles ORDER BY id_rol");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerPermisos() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_permiso, nombre_permiso, descripcion_permiso FROM lista_permisos ORDER BY id_permiso");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerRolesPermisos() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_rol, id_permiso FROM roles_permisos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function actualizarPermisosRol($idRol, $permisosIds) {
        try {
            $this->pdo->beginTransaction();
            $stmtDel = $this->pdo->prepare("DELETE FROM roles_permisos WHERE id_rol = :id_rol");
            $stmtDel->execute([':id_rol' => (int)$idRol]);

            if (!empty($permisosIds) && is_array($permisosIds)) {
                $stmtIns = $this->pdo->prepare("INSERT INTO roles_permisos (id_rol, id_permiso) VALUES (:id_rol, :id_permiso)");
                foreach ($permisosIds as $idPermiso) {
                    $stmtIns->execute([
                        ':id_rol' => (int)$idRol,
                        ':id_permiso' => (int)$idPermiso
                    ]);
                }
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function crearRol($nombre, $descripcion) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO lista_roles (nombre_rol, descripcion_rol) VALUES (:nombre, :descripcion)");
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarRol($idRol) {
        try {
            $this->pdo->beginTransaction();
            $rolDefecto = $this->obtenerRolDefecto();
            if ((int)$idRol === (int)$rolDefecto) {
                $stmtNext = $this->pdo->prepare("SELECT id_rol FROM lista_roles WHERE id_rol != :id_rol LIMIT 1");
                $stmtNext->execute([':id_rol' => (int)$idRol]);
                $nextDefecto = $stmtNext->fetchColumn();
                if ($nextDefecto !== false) {
                    $stmtUpdateDef = $this->pdo->prepare("UPDATE configuracion SET rol_defecto = :next_def");
                    $stmtUpdateDef->execute([':next_def' => (int)$nextDefecto]);
                    $rolDefecto = $nextDefecto;
                } else {
                    $rolDefecto = null;
                }
            }

            if ($rolDefecto !== null) {
                $stmtUser = $this->pdo->prepare("UPDATE usuarios SET rol = :rol_defecto WHERE rol = :id_rol");
                $stmtUser->execute([
                    ':rol_defecto' => $rolDefecto,
                    ':id_rol' => (int)$idRol
                ]);
            } else {
                $stmtUser = $this->pdo->prepare("UPDATE usuarios SET rol = 0 WHERE rol = :id_rol");
                $stmtUser->execute([':id_rol' => (int)$idRol]);
            }

            $stmtRP = $this->pdo->prepare("DELETE FROM roles_permisos WHERE id_rol = :id_rol");
            $stmtRP->execute([':id_rol' => (int)$idRol]);

            $stmtRol = $this->pdo->prepare("DELETE FROM lista_roles WHERE id_rol = :id_rol");
            $stmtRol->execute([':id_rol' => (int)$idRol]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function actualizarRol($idRol, $nombre, $descripcion) {
        try {
            $stmt = $this->pdo->prepare("UPDATE lista_roles SET nombre_rol = :nombre, descripcion_rol = :descripcion WHERE id_rol = :id_rol");
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':id_rol' => (int)$idRol
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerRolDefecto() {
        try {
            $stmt = $this->pdo->prepare("SELECT rol_defecto FROM configuracion LIMIT 1");
            $stmt->execute();
            $rol = $stmt->fetchColumn();
            if ($rol === false) {
                $this->pdo->exec("INSERT INTO configuracion (rol_defecto) VALUES (3)");
                return 3;
            }
            return (int)$rol;
        } catch (PDOException $e) {
            return 3;
        }
    }

    public function actualizarRolDefecto($idRol) {
        try {
            $stmtCheck = $this->pdo->prepare("SELECT COUNT(*) FROM configuracion");
            $stmtCheck->execute();
            if ($stmtCheck->fetchColumn() == 0) {
                $stmt = $this->pdo->prepare("INSERT INTO configuracion (rol_defecto) VALUES (:rol)");
            } else {
                $stmt = $this->pdo->prepare("UPDATE configuracion SET rol_defecto = :rol");
            }
            return $stmt->execute([':rol' => (int)$idRol]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crearPermiso($nombre, $descripcion) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO lista_permisos (nombre_permiso, descripcion_permiso) VALUES (:nombre, :descripcion)");
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function registrarPermiso($nombre, $descripcion = '') {
        try {
            $stmt = $this->pdo->prepare("SELECT id_permiso FROM lista_permisos WHERE nombre_permiso = :nombre");
            $stmt->execute([':nombre' => $nombre]);
            if ($stmt->fetchColumn() === false) {
                $stmtIns = $this->pdo->prepare("INSERT INTO lista_permisos (nombre_permiso, descripcion_permiso) VALUES (:nombre, :descripcion)");
                $stmtIns->execute([
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion
                ]);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function sincronizarPermisos(array $permisosActivos) {
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("SELECT id_permiso, nombre_permiso FROM lista_permisos");
            $stmt->execute();
            $permisosDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $dbNombres = [];
            foreach ($permisosDB as $p) {
                $dbNombres[$p['nombre_permiso']] = $p['id_permiso'];
            }
            
            $stmtIns = $this->pdo->prepare("INSERT INTO lista_permisos (nombre_permiso, descripcion_permiso) VALUES (:nombre, :descripcion)");
            $stmtUpd = $this->pdo->prepare("UPDATE lista_permisos SET descripcion_permiso = :descripcion WHERE nombre_permiso = :nombre");
            foreach ($permisosActivos as $nombre => $descripcion) {
                if (!isset($dbNombres[$nombre])) {
                    $stmtIns->execute([
                        ':nombre' => $nombre,
                        ':descripcion' => $descripcion
                    ]);
                } else {
                    $stmtUpd->execute([
                        ':nombre' => $nombre,
                        ':descripcion' => $descripcion
                    ]);
                }
            }
            
            $nombresCodigo = array_keys($permisosActivos);
            foreach ($dbNombres as $nombre => $idPermiso) {
                if (!in_array($nombre, $nombresCodigo)) {
                    $stmtDelRP = $this->pdo->prepare("DELETE FROM roles_permisos WHERE id_permiso = :id_permiso");
                    $stmtDelRP->execute([':id_permiso' => $idPermiso]);
                    
                    $stmtDelP = $this->pdo->prepare("DELETE FROM lista_permisos WHERE id_permiso = :id_permiso");
                    $stmtDelP->execute([':id_permiso' => $idPermiso]);
                }
            }
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function eliminarPermiso($idPermiso) {
        try {
            $this->pdo->beginTransaction();
            $stmtRP = $this->pdo->prepare("DELETE FROM roles_permisos WHERE id_permiso = :id_permiso");
            $stmtRP->execute([':id_permiso' => (int)$idPermiso]);

            $stmtPerm = $this->pdo->prepare("DELETE FROM lista_permisos WHERE id_permiso = :id_permiso");
            $stmtPerm->execute([':id_permiso' => (int)$idPermiso]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function obtenerTipos() {
        try {
            $stmt = $this->pdo->prepare("SELECT id_tipo, nombre_tipo FROM lista_tipos ORDER BY id_tipo ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}