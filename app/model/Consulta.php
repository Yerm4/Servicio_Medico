<?php
namespace app\model;

use PDO;
use PDOException;

class Consulta {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function obtenerTodosLosUsuarios() {
        try {
            $sql = "SELECT cedula, nombre, apellido, tipo FROM usuarios WHERE activo = 1 ORDER BY apellido, nombre";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function buscarUsuario($query) {
        try {
            $sql = "SELECT cedula, nombre, apellido FROM usuarios 
                    WHERE (cedula LIKE :query OR nombre LIKE :query OR apellido LIKE :query) 
                    AND activo = 1
                    LIMIT 10";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':query' => '%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function buscarPatologia($query) {
        try {
            $sql = "SELECT codigo_icd, patologia FROM lista_patologias 
                    WHERE codigo_icd LIKE :query OR patologia LIKE :query 
                    LIMIT 10";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':query' => '%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function buscarCondicion($query) {
        try {
            $sql = "SELECT id, condicion FROM lista_condiciones 
                    WHERE condicion LIKE :query 
                    LIMIT 10";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':query' => '%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerCondicionesPaciente($cedula) {
        try {
            $sql = "SELECT c.id, c.condicion 
                    FROM condiciones_usuarios cu
                    INNER JOIN lista_condiciones c ON cu.id_condicion = c.id
                    WHERE cu.cedula_usuario = :cedula";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cedula' => (int)$cedula]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function registrarConsulta($cedulaPaciente, $cedulaMedico, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones = [], $medicamentoSuministrado = '') {
        try {
            $this->db->beginTransaction();

            // 1. Insert into consulta_medica
            $sqlConsulta = "INSERT INTO consulta_medica (id_usuario, id_medico, motivo_de_visita, observaciones, medicamento_suministrado) 
                            VALUES (:id_usuario, :id_medico, :motivo, :observaciones, :medicamento_suministrado)";
            $stmtConsulta = $this->db->prepare($sqlConsulta);
            $stmtConsulta->execute([
                ':id_usuario' => (int)$cedulaPaciente,
                ':id_medico' => (int)$cedulaMedico,
                ':motivo' => $motivo,
                ':observaciones' => $observaciones,
                ':medicamento_suministrado' => $medicamentoSuministrado
            ]);

            $idConsulta = $this->db->lastInsertId();

            if (!empty($sintomas) && is_array($sintomas)) {
                foreach ($sintomas as $sintoma) {
                    $sintomaClean = trim($sintoma);
                    if ($sintomaClean !== '') {
                        $stmtGet = $this->db->prepare("SELECT id_sintoma FROM lista_sintomas WHERE nombre_sintoma = :nombre");
                        $stmtGet->execute([':nombre' => $sintomaClean]);
                        $idSintoma = $stmtGet->fetchColumn();
                        if (!$idSintoma) {
                            $stmtIns = $this->db->prepare("INSERT INTO lista_sintomas (nombre_sintoma) VALUES (:nombre)");
                            $stmtIns->execute([':nombre' => $sintomaClean]);
                            $idSintoma = $this->db->lastInsertId();
                        }
                        $stmtSintoma = $this->db->prepare("INSERT IGNORE INTO sintomas_consulta (id_consulta, id_sintoma) VALUES (:id_consulta, :id_sintoma)");
                        $stmtSintoma->execute([
                            ':id_consulta' => $idConsulta,
                            ':id_sintoma' => $idSintoma
                        ]);
                    }
                }
            }

            // 3. Insert diagnoses into diagnosticos_consulta
            if (!empty($diagnosticos) && is_array($diagnosticos)) {
                $sqlDiagnostico = "INSERT INTO diagnosticos_consulta (id_consulta, codigo_icd_diagnostico) 
                                   VALUES (:id_consulta, :codigo_icd)";
                $stmtDiagnostico = $this->db->prepare($sqlDiagnostico);
                foreach ($diagnosticos as $codigoIcd) {
                    $codigoIcdClean = trim($codigoIcd);
                    if ($codigoIcdClean !== '') {
                        $stmtDiagnostico->execute([
                            ':id_consulta' => $idConsulta,
                            ':codigo_icd' => $codigoIcdClean
                        ]);
                    }
                }
            }

            $sqlDelCondiciones = "DELETE FROM condiciones_usuarios WHERE cedula_usuario = :cedula";
            $stmtDelCond = $this->db->prepare($sqlDelCondiciones);
            $stmtDelCond->execute([':cedula' => (int)$cedulaPaciente]);

            if (!empty($condiciones) && is_array($condiciones)) {
                $sqlInsCondicion = "INSERT INTO condiciones_usuarios (cedula_usuario, id_condicion) VALUES (:cedula, :id_condicion)";
                $stmtInsCond = $this->db->prepare($sqlInsCondicion);
                foreach ($condiciones as $idCondicion) {
                    $idCondicionClean = (int)$idCondicion;
                    if ($idCondicionClean > 0) {
                        $stmtInsCond->execute([
                            ':cedula' => (int)$cedulaPaciente,
                            ':id_condicion' => $idCondicionClean
                        ]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Error al registrar la consulta médica: " . $e->getMessage();
        }
    }

    public function obtenerConsultasPorPaciente($cedula) {
        try {
            $sql = "SELECT c.id, c.id_usuario, c.id_medico, c.motivo_de_visita, c.observaciones, c.medicamento_suministrado, c.fecha_consulta,
                           u.nombre AS medico_nombre, u.apellido AS medico_apellido 
                    FROM consulta_medica c
                    LEFT JOIN usuarios u ON c.id_medico = u.cedula
                    WHERE c.id_usuario = :cedula
                    ORDER BY c.fecha_consulta DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cedula' => $cedula]);
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sqlCondiciones = "SELECT c.condicion 
                               FROM condiciones_usuarios cu
                               INNER JOIN lista_condiciones c ON cu.id_condicion = c.id
                               WHERE cu.cedula_usuario = :cedula";
            $stmtC = $this->db->prepare($sqlCondiciones);
            $stmtC->execute([':cedula' => $cedula]);
            $condiciones = $stmtC->fetchAll(PDO::FETCH_COLUMN);

            foreach ($consultas as &$c) {
                $c['condiciones_cronicas'] = $condiciones;

                $sqlSintomas = "SELECT l.nombre_sintoma FROM sintomas_consulta s INNER JOIN lista_sintomas l ON s.id_sintoma = l.id_sintoma WHERE s.id_consulta = :id_consulta";
                $stmtS = $this->db->prepare($sqlSintomas);
                $stmtS->execute([':id_consulta' => $c['id']]);
                $c['sintomas'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);

                // Fetch diagnoses
                $sqlDiagnosticos = "SELECT d.codigo_icd_diagnostico, p.patologia 
                                    FROM diagnosticos_consulta d
                                    LEFT JOIN lista_patologias p ON d.codigo_icd_diagnostico = p.codigo_icd
                                    WHERE d.id_consulta = :id_consulta";
                $stmtD = $this->db->prepare($sqlDiagnosticos);
                $stmtD->execute([':id_consulta' => $c['id']]);
                $c['diagnosticos'] = $stmtD->fetchAll(PDO::FETCH_ASSOC);
            }

            return $consultas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function actualizarConsulta($idConsulta, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones = [], $medicamentoSuministrado = '') {
        try {
            $this->db->beginTransaction();

            // Update main record
            $sql = "UPDATE consulta_medica SET motivo_de_visita = :motivo, observaciones = :observaciones, medicamento_suministrado = :medicamento_suministrado 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':motivo' => $motivo,
                ':observaciones' => $observaciones,
                ':medicamento_suministrado' => $medicamentoSuministrado,
                ':id' => $idConsulta
            ]);

            $sqlDelSintomas = "DELETE FROM sintomas_consulta WHERE id_consulta = :id_consulta";
            $stmtDelS = $this->db->prepare($sqlDelSintomas);
            $stmtDelS->execute([':id_consulta' => $idConsulta]);

            if (!empty($sintomas) && is_array($sintomas)) {
                foreach ($sintomas as $sintoma) {
                    $sintomaClean = trim($sintoma);
                    if ($sintomaClean !== '') {
                        $stmtGet = $this->db->prepare("SELECT id_sintoma FROM lista_sintomas WHERE nombre_sintoma = :nombre");
                        $stmtGet->execute([':nombre' => $sintomaClean]);
                        $idSintoma = $stmtGet->fetchColumn();
                        if (!$idSintoma) {
                            $stmtIns = $this->db->prepare("INSERT INTO lista_sintomas (nombre_sintoma) VALUES (:nombre)");
                            $stmtIns->execute([':nombre' => $sintomaClean]);
                            $idSintoma = $this->db->lastInsertId();
                        }
                        $stmtSintoma = $this->db->prepare("INSERT IGNORE INTO sintomas_consulta (id_consulta, id_sintoma) VALUES (:id_consulta, :id_sintoma)");
                        $stmtSintoma->execute([
                            ':id_consulta' => $idConsulta,
                            ':id_sintoma' => $idSintoma
                        ]);
                    }
                }
            }

            // Delete old diagnoses
            $sqlDelDiagnosticos = "DELETE FROM diagnosticos_consulta WHERE id_consulta = :id_consulta";
            $stmtDelD = $this->db->prepare($sqlDelDiagnosticos);
            $stmtDelD->execute([':id_consulta' => $idConsulta]);

            // Insert new diagnoses
            if (!empty($diagnosticos) && is_array($diagnosticos)) {
                $sqlInsDiagnostico = "INSERT INTO diagnosticos_consulta (id_consulta, codigo_icd_diagnostico) 
                                      VALUES (:id_consulta, :codigo_icd)";
                $stmtInsD = $this->db->prepare($sqlInsDiagnostico);
                foreach ($diagnosticos as $codigoIcd) {
                    $codigoIcdClean = trim($codigoIcd);
                    if ($codigoIcdClean !== '') {
                        $stmtInsD->execute([
                            ':id_consulta' => $idConsulta,
                            ':codigo_icd' => $codigoIcdClean
                        ]);
                    }
                }
            }

            $sqlCedula = "SELECT id_usuario FROM consulta_medica WHERE id = :id";
            $stmtCedula = $this->db->prepare($sqlCedula);
            $stmtCedula->execute([':id' => $idConsulta]);
            $cedulaPaciente = $stmtCedula->fetchColumn();

            if ($cedulaPaciente) {
                $sqlDelCondiciones = "DELETE FROM condiciones_usuarios WHERE cedula_usuario = :cedula";
                $stmtDelCond = $this->db->prepare($sqlDelCondiciones);
                $stmtDelCond->execute([':cedula' => (int)$cedulaPaciente]);

                if (!empty($condiciones) && is_array($condiciones)) {
                    $sqlInsCondicion = "INSERT INTO condiciones_usuarios (cedula_usuario, id_condicion) VALUES (:cedula, :id_condicion)";
                    $stmtInsCond = $this->db->prepare($sqlInsCondicion);
                    foreach ($condiciones as $idCondicion) {
                        $idCondicionClean = (int)$idCondicion;
                        if ($idCondicionClean > 0) {
                            $stmtInsCond->execute([
                                ':cedula' => (int)$cedulaPaciente,
                                ':id_condicion' => $idCondicionClean
                            ]);
                        }
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Error al actualizar la consulta médica: " . $e->getMessage();
        }
    }

    public function obtenerConsultasRecientes($limit = 10, $offset = 0) {
        try {
            $sql = "SELECT c.id, c.id_usuario, c.id_medico, c.motivo_de_visita, c.observaciones, c.medicamento_suministrado, c.fecha_consulta,
                           u.nombre AS paciente_nombre, u.apellido AS paciente_apellido,
                           m.nombre AS medico_nombre, m.apellido AS medico_apellido 
                    FROM consulta_medica c
                    LEFT JOIN usuarios u ON c.id_usuario = u.cedula
                    LEFT JOIN usuarios m ON c.id_medico = m.cedula
                    ORDER BY c.fecha_consulta DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($consultas as &$c) {
                $sqlSintomas = "SELECT l.nombre_sintoma FROM sintomas_consulta s INNER JOIN lista_sintomas l ON s.id_sintoma = l.id_sintoma WHERE s.id_consulta = :id_consulta";
                $stmtS = $this->db->prepare($sqlSintomas);
                $stmtS->execute([':id_consulta' => $c['id']]);
                $c['sintomas'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);

                $sqlDiagnosticos = "SELECT d.codigo_icd_diagnostico, p.patologia 
                                    FROM diagnosticos_consulta d
                                    LEFT JOIN lista_patologias p ON d.codigo_icd_diagnostico = p.codigo_icd
                                    WHERE d.id_consulta = :id_consulta";
                $stmtD = $this->db->prepare($sqlDiagnosticos);
                $stmtD->execute([':id_consulta' => $c['id']]);
                $c['diagnosticos'] = $stmtD->fetchAll(PDO::FETCH_ASSOC);
            }

            return $consultas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function buscarConsultas($query, $limit = 20, $offset = 0) {
        try {
            $sql = "SELECT c.id, c.id_usuario, c.id_medico, c.motivo_de_visita, c.observaciones, c.medicamento_suministrado, c.fecha_consulta,
                           u.nombre AS paciente_nombre, u.apellido AS paciente_apellido,
                           m.nombre AS medico_nombre, m.apellido AS medico_apellido 
                    FROM consulta_medica c
                    LEFT JOIN usuarios u ON c.id_usuario = u.cedula
                    LEFT JOIN usuarios m ON c.id_medico = m.cedula
                    WHERE (c.id_usuario LIKE :query 
                       OR u.nombre LIKE :query 
                       OR u.apellido LIKE :query 
                       OR m.nombre LIKE :query 
                       OR m.apellido LIKE :query
                       OR c.motivo_de_visita LIKE :query)
                    ORDER BY c.fecha_consulta DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($consultas as &$c) {
                $sqlSintomas = "SELECT l.nombre_sintoma FROM sintomas_consulta s INNER JOIN lista_sintomas l ON s.id_sintoma = l.id_sintoma WHERE s.id_consulta = :id_consulta";
                $stmtS = $this->db->prepare($sqlSintomas);
                $stmtS->execute([':id_consulta' => $c['id']]);
                $c['sintomas'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);

                $sqlDiagnosticos = "SELECT d.codigo_icd_diagnostico, p.patologia 
                                    FROM diagnosticos_consulta d
                                    LEFT JOIN lista_patologias p ON d.codigo_icd_diagnostico = p.codigo_icd
                                    WHERE d.id_consulta = :id_consulta";
                $stmtD = $this->db->prepare($sqlDiagnosticos);
                $stmtD->execute([':id_consulta' => $c['id']]);
                $c['diagnosticos'] = $stmtD->fetchAll(PDO::FETCH_ASSOC);
            }

            return $consultas;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerConsultaPorId($idConsulta) {
        try {
            $sql = "SELECT c.id, c.id_usuario, c.id_medico, c.motivo_de_visita, c.observaciones, c.medicamento_suministrado, c.fecha_consulta,
                           u.nombre AS paciente_nombre, u.apellido AS paciente_apellido,
                           m.nombre AS medico_nombre, m.apellido AS medico_apellido 
                    FROM consulta_medica c
                    LEFT JOIN usuarios u ON c.id_usuario = u.cedula
                    LEFT JOIN usuarios m ON c.id_medico = m.cedula
                    WHERE c.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => (int)$idConsulta]);
            $c = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$c) {
                return null;
            }

            $sqlSintomas = "SELECT l.nombre_sintoma FROM sintomas_consulta s INNER JOIN lista_sintomas l ON s.id_sintoma = l.id_sintoma WHERE s.id_consulta = :id_consulta";
            $stmtS = $this->db->prepare($sqlSintomas);
            $stmtS->execute([':id_consulta' => $c['id']]);
            $c['sintomas'] = $stmtS->fetchAll(PDO::FETCH_COLUMN);

            $sqlDiagnosticos = "SELECT d.codigo_icd_diagnostico, p.patologia 
                                FROM diagnosticos_consulta d
                                LEFT JOIN lista_patologias p ON d.codigo_icd_diagnostico = p.codigo_icd
                                WHERE d.id_consulta = :id_consulta";
            $stmtD = $this->db->prepare($sqlDiagnosticos);
            $stmtD->execute([':id_consulta' => $c['id']]);
            $c['diagnosticos'] = $stmtD->fetchAll(PDO::FETCH_ASSOC);

            return $c;
        } catch (PDOException $e) {
            return null;
        }
    }
}
