<?php
require_once __DIR__."/../vendor/autoload.php";
use app\controller\Controller;
use app\controller\PacienteController;
use app\controller\ConsultaController;
use app\model\Consulta;
use app\config\Config;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
} else {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
}
$dotenv->load();

$appEnv = $_ENV['APP_ENV'] ?? 'production';
if ($appEnv === 'local') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}


session_start();

if (isset($_SESSION['cedula'])) {
    if (!isset($_SESSION['user_agent']) || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        session_unset();
        session_destroy();
        header("Location: login");
        exit();
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = Config::conexion(); 
$controller = new Controller($pdo);
$controllerPaciente = new PacienteController($pdo);
$controllerConsulta = new ConsultaController($pdo);
$userModel = new \app\model\Usuario($pdo);
$userModel->sincronizarPermisos([
    "gestionar_usuarios" => "Permite registrar, actualizar y eliminar usuarios",
    "ver_consultas" => "Permite ver y buscar el historial de consultas médicas",
    "realizar_consulta" => "Permite registrar una nueva consulta médica",
    "modificar_consulta" => "Permite registrar y actualizar consultas médicas",
    "generar_reportes" => "Permite generar reportes de morbilidad médica",
    "gestionar_roles_permisos" => "Permite administrar roles, permisos y configuración del sistema"
]);

// Auto-associate default permissions and clean up Director role to remove ver/modify/add consultations
try {
    // Enforce Director (4) shouldn't have ver_consultas, realizar_consulta, modificar_consulta
    $pIdsToRemove = [];
    $stmtP = $pdo->prepare("SELECT id_permiso FROM lista_permisos WHERE nombre_permiso IN ('ver_consultas', 'realizar_consulta', 'modificar_consulta')");
    $stmtP->execute();
    $pIdsToRemove = $stmtP->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($pIdsToRemove)) {
        $inClause = implode(',', array_map('intval', $pIdsToRemove));
        $stmtDel = $pdo->prepare("DELETE FROM roles_permisos WHERE id_rol = 4 AND id_permiso IN ($inClause)");
        $stmtDel->execute();
    }

    $insertMap = [
        2 => ['gestionar_usuarios', 'ver_consultas', 'realizar_consulta', 'modificar_consulta'],
        3 => ['gestionar_usuarios', 'ver_consultas', 'realizar_consulta', 'modificar_consulta', 'generar_reportes'],
        4 => ['gestionar_usuarios', 'generar_reportes', 'gestionar_roles_permisos']
    ];

    foreach ($insertMap as $roleId => $permNames) {
        foreach ($permNames as $pName) {
            $stmtId = $pdo->prepare("SELECT id_permiso FROM lista_permisos WHERE nombre_permiso = :name");
            $stmtId->execute([':name' => $pName]);
            $idPerm = $stmtId->fetchColumn();
            if ($idPerm) {
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM roles_permisos WHERE id_rol = :role_id AND id_permiso = :id_perm");
                $stmtCheck->execute([':role_id' => $roleId, ':id_perm' => $idPerm]);
                if ($stmtCheck->fetchColumn() == 0) {
                    $stmtIns = $pdo->prepare("INSERT INTO roles_permisos (id_rol, id_permiso) VALUES (:role_id, :id_perm)");
                    $stmtIns->execute([':role_id' => $roleId, ':id_perm' => $idPerm]);
                }
            }
        }
    }
} catch (Exception $e) {
    // Ignore database errors during bootstrap
}

function checkPerm(string $permiso, \app\model\Usuario $userModel): bool {
    if (!isset($_SESSION['cedula'])) {
        return false;
    }
    return $userModel->tienePermiso($_SESSION['cedula'], $permiso);
}

$tieneGestionarUsuarios = false;
$tieneVerConsultas = false;
$tieneRealizarConsulta = false;
$tieneModificarConsulta = false;
$tieneGenerarReportes = false;
$tieneGestionarRolesPermisos = false;
$rolUsuario = 0;
if (isset($_SESSION['cedula'])) {
    $datosUsuarioLogueado = $userModel->loginUsuario($_SESSION['cedula']);
    $rolUsuario = isset($datosUsuarioLogueado['rol']) ? (int)$datosUsuarioLogueado['rol'] : 0;

    $tieneGestionarUsuarios = checkPerm("gestionar_usuarios", $userModel);
    $tieneVerConsultas = checkPerm("ver_consultas", $userModel);
    $tieneRealizarConsulta = checkPerm("realizar_consulta", $userModel);
    $tieneModificarConsulta = checkPerm("modificar_consulta", $userModel);
    $tieneGenerarReportes = checkPerm("generar_reportes", $userModel);
    $tieneGestionarRolesPermisos = checkPerm("gestionar_roles_permisos", $userModel);
}

$ruta = isset($_GET["ruta"]) ? trim($_GET["ruta"], "/") : "login";
$partesRuta = explode("/", $ruta);
$paginaActual = $partesRuta[0];

if (!isset($_SESSION['cedula'])) {
    if (in_array($paginaActual, ["perfil", "usuarios", "consultas", "configuracion", "sesion"])) {
        header("Location: login");
        exit();
    }
} else {
    if ($paginaActual === "usuarios" && !$tieneGestionarUsuarios) {
        header("Location: perfil");
        exit();
    }
    if ($paginaActual === "consultas" && !$tieneVerConsultas) {
        header("Location: perfil");
        exit();
    }
    if ($paginaActual === "configuracion" && !$tieneGestionarRolesPermisos) {
        header("Location: perfil");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit("CSRF token validation failed");
    }

    $form = isset($_POST["form"]) ? $_POST["form"] : '';
    switch($form) {
        case "registro_paciente":
            if (isset($_SESSION['cedula'])) {
                if (!checkPerm("gestionar_usuarios", $userModel)) {
                    $_SESSION["registro_status"] = "error";
                    $_SESSION["registro_msg"] = "No tiene permisos para registrar pacientes.";
                    header("Location: perfil");
                    exit();
                }
            }
            $controllerPaciente->Registrar();    
            break;
        case "registro_consulta":
            if (!$tieneRealizarConsulta) {
                http_response_code(403);
                exit("No tiene permisos para registrar consultas.");
            }
            $controllerConsulta->registrar();
            break;
        case "actualizar_consulta":
            if (!$tieneModificarConsulta) {
                http_response_code(403);
                exit("No tiene permisos para actualizar consultas.");
            }
            $controllerConsulta->actualizar();
            break;
        case "eliminar_usuario":
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'No tiene permisos para eliminar usuarios.']);
                exit();
            }
            $controller->eliminar(); 
            break;
        case "login":
            $controller->login();
            break;
        case "buscar_usuario":
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode([]);
                exit();
            }
            $controller->buscar();
            break;
        case "obtener_usuario":
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No tiene permisos.']);
                exit();
            }
            $controller->obtenerUsuarioPorCedula();
            break;
        case "actualizar_usuario":
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'No tiene permisos para actualizar usuarios.']);
                exit();
            }
            $controller->actualizar();
            break;
        case "buscar_consultas":
            if (!checkPerm("ver_consultas", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode([]);
                exit();
            }
            $controllerConsulta->buscarConsultasAjax();
            break;
        case "obtener_consulta":
            if (!checkPerm("ver_consultas", $userModel)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'No tiene permisos.']);
                exit();
            }
            $controllerConsulta->obtenerConsultaPorIdAjax();
            break;
        case "guardar_roles_permisos":
            if (!checkPerm("gestionar_roles_permisos", $userModel)) {
                http_response_code(403);
                exit("No tiene permisos para administrar roles y permisos.");
            }
            $roles = $userModel->obtenerRoles();
            $postedPermisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];
            foreach ($roles as $role) {
                $idRol = $role['id_rol'];
                $permisosIds = isset($postedPermisos[$idRol]) ? $postedPermisos[$idRol] : [];
                $userModel->actualizarPermisosRol($idRol, $permisosIds);
            }
            $_SESSION["registro_status"] = "success";
            $_SESSION["registro_msg"] = "¡Roles y permisos actualizados con éxito!";
            header("Location: configuracion");
            exit();
            break;
        case "registrar_rol":
            if (!checkPerm("gestionar_roles_permisos", $userModel)) {
                http_response_code(403);
                exit("No tiene permisos para administrar roles y permisos.");
            }
            $nombreRol = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';
            $descripcionRol = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
            if (!empty($nombreRol)) {
                $userModel->crearRol($nombreRol, $descripcionRol);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Rol creado exitosamente!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "El nombre del rol es obligatorio.";
            }
            header("Location: configuracion");
            exit();
            break;
        case "eliminar_rol":
            if (!checkPerm("gestionar_roles_permisos", $userModel)) {
                http_response_code(403);
                exit("No tiene permisos para administrar roles y permisos.");
            }
            $idRol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
            if ($idRol > 0) {
                $userModel->eliminarRol($idRol);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Rol eliminado con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "No se puede eliminar este rol.";
            }
            header("Location: configuracion");
            exit();
            break;
        case "actualizar_rol":
            if (!checkPerm("gestionar_roles_permisos", $userModel)) {
                http_response_code(403);
                exit("No tiene permisos para administrar roles y permisos.");
            }
            $idRol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
            $nombreRol = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';
            $descripcionRol = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
            if ($idRol > 0 && !empty($nombreRol)) {
                $userModel->actualizarRol($idRol, $nombreRol, $descripcionRol);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Rol actualizado con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "Datos del rol inválidos.";
            }
            header("Location: configuracion");
            exit();
            break;
        case "guardar_configuracion":
            if (!checkPerm("gestionar_roles_permisos", $userModel)) {
                http_response_code(403);
                exit("No tiene permisos para administrar roles y permisos.");
            }
            $rolDefecto = isset($_POST['rol_defecto']) ? (int)$_POST['rol_defecto'] : 0;
            if ($rolDefecto > 0) {
                $userModel->actualizarRolDefecto($rolDefecto);
                $_SESSION["registro_status"] = "success";
                $_SESSION["registro_msg"] = "¡Configuración general guardada con éxito!";
            } else {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "Seleccione un rol válido.";
            }
            header("Location: configuracion");
            exit();
            break;
    }

}



if ($paginaActual === "buscar_patologia") {
    if (!checkPerm("ver_consultas", $userModel)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }
    $controllerConsulta->buscarPatologiaAjax();
    exit();
}

if ($paginaActual === "buscar_paciente") {
    if (!checkPerm("ver_consultas", $userModel)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }
    $controllerConsulta->buscarPacienteAjax();
    exit();
}

if ($paginaActual === "buscar_consultas_paciente") {
    if (!checkPerm("ver_consultas", $userModel)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }
    $controllerConsulta->obtenerConsultasPacienteAjax();
    exit();
}

if ($paginaActual === "buscar_condicion") {
    if (!checkPerm("ver_consultas", $userModel)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }
    $controllerConsulta->buscarCondicionAjax();
    exit();
}

if ($paginaActual === "buscar_condiciones_paciente") {
    if (!checkPerm("ver_consultas", $userModel)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit();
    }
    $controllerConsulta->obtenerCondicionesPacienteAjax();
    exit();
}

$todosLosUsuarios = [];
if (isset($_SESSION['cedula']) && $tieneGestionarUsuarios) {
    $modeloConsulta = new Consulta($pdo);
    $todosLosUsuarios = $modeloConsulta->obtenerTodosLosUsuarios();
}

include __DIR__."/../app/views/header.php";

switch($paginaActual) {
    case "login":
        include __DIR__."/../app/views/login.php";
        break;

    case "registro":
        include __DIR__."/../app/views/registro.php";
        break;

    case "perfil": 
    case "usuarios":
    case "consultas":
    case "configuracion":
    case "sesion":
        include __DIR__."/../app/views/perfil.php";
        break;

    case "logout":
        session_unset();
        session_destroy();
        header("Location: login"); 
        exit();
        break;

    default: 
        include __DIR__."/../app/views/login.php";
        break;
}


function e(?string $value, bool $doubleEncode = true): string {
    if ($value === null) {
        return '';
    }
    
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
}


function calcularEdad(?string $fechaNacimiento): string {
    if (empty($fechaNacimiento)) {
        return "No registrado";
    }

    try {
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $fechaActual = new DateTime();
        if ($fechaNacimiento > $fechaActual) {
            return "Fecha invalida";
        }

        if ((int)$fechaNacimiento->format('Y') < 1900) {
            return 'Fecha inválida';
        }

        return (string)$fechaActual->diff($fechaNacimiento)->y;
    }

    catch (Exception $e) {
        return 'Error de formato';
    }
}


$rawUri = $_SERVER['REQUEST_URI'];
$cleanPath = parse_url($rawUri, PHP_URL_PATH);
$currentPage = trim($cleanPath, '/');

?>
