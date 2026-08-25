<?php
session_start();
require_once __DIR__."/../vendor/autoload.php";

use app\controller\Controller;
use app\controller\ConsultaController;
use app\controller\CondicionController;
use app\controller\NucleoPNFController;
use app\controller\ViewController;
use app\model\Consulta;
use app\config\Config;

$pdo = Config::conexion(); 
$controller = new Controller($pdo);
$controllerConsulta = new ConsultaController($pdo);
$controllerOferta = new NucleoPNFController($pdo);
$viewController = new ViewController($pdo);  


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

//$form = isset($_POST["form"]) ? $_POST["form"] : '';

$method = !empty($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";

$rutasApi = [
    "GET" => [
        "api/users/buscar"                  => "buscarUsuario",
        "api/users/cedula"                  => "obtenerUsuario",
        "api/consultas"                     => "buscarConsultas",
        "api/consultas/detalle"             => "obtenerConsulta",
        "api/consultas/reporte-morbilidad"  => "generarReporteMorbilidad",
        "api/pnfs"                          => "buscarPnfs",
        "api/nucleos/pnfs"                  => "obtenerPnfsPorNucleo"
    ],
    "POST" => [
        "api/auth/login"                    => "login",
        "api/users"                         => "registrarUsuario",
        "api/consultas"                     => "registroConsulta",
        "api/roles"                         => "registrarRol",
        "api/condiciones"                   => "registrarCondicion",
        "api/nucleos"                       => "registrarNucleo",
        "api/pnfs"                          => "registrarPnf",
        "api/ofertas"                       => "registrarOferta"
    ],
    "PUT" => [
        "api/users"                         => "actualizarUsuario",
        "api/consultas"                     => "actualizarConsulta",
        "api/roles"                         => "actualizarRol",
        "api/roles/permisos"                => "guardarRolesPermisos",
        "api/configuracion"                 => "guardarConfiguracion",
        "api/condiciones"                   => "actualizarCondicion",
        "api/nucleos"                       => "actualizarNucleo",
        "api/pnfs"                          => "actualizarPnf"
    ],
    "PATCH" => [
        // Reservado para actualizaciones parciales
    ],
    "DELETE" => [
        "api/users"                         => "eliminarUsuario",
        "api/roles"                         => "eliminarRol",
        "api/condiciones"                   => "eliminarCondicion",
        "api/nucleos"                       => "eliminarNucleo",
        "api/pnfs"                          => "eliminarPnf",
        "api/ofertas"                       => "eliminarOferta"
    ]
];

$ruta = isset($_GET["ruta"]) ? trim($_GET["ruta"], "/") : "login";
$partesRuta = explode("/", $ruta);
$paginaActual = $partesRuta[0];
$paginaMostrar = __DIR__."/../app/views/$paginaActual.php";

if ($paginaActual === "logout") {
    session_unset();
    session_destroy();
    header("Location: login"); 
    exit();
}

if (isset($rutasApi[$method][$ruta])) {
    header("Content-Type: application/json; charset=UTF-8");
    
    $metodoController = $rutasApi[$method][$ruta];

    if (method_exists($apiController, $metodoController)) {
        $apiController->$metodoController();
        exit();
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "El método del controlador no existe"]);
        exit();
    }
}

if (str_starts_with($ruta, "api/")) { 
    header("Content-Type: application/json; 
    charset=UTF-8"); http_response_code(400); 
    echo json_encode([ 
        "status" => "error", 
        "message" => "La ruta: ".$ruta." no existe" 
        ]); 
    exit(); 
}

/*switch($form) {
    case "registrar_usuario":
        if (isset($_SESSION['cedula'])) {
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "No tiene permisos para registrar pacientes.";
                header("Location: perfil");
                exit();
            }
        }
        $controller->Registrar();    
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
    case "generar_reporte_morbilidad":
        if (!$tieneGenerarReportes) {
            http_response_code(403);
            exit("No tiene permisos para generar reportes.");
        }
        $controllerConsulta->generarReporteMorbilidad();
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
    case "registrar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->registrar();
        break;
    case "actualizar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->actualizar();
        break;
    case "eliminar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->eliminar();
        break;
    case "buscar_pnfs":
        $controller = new Controller($pdo);
        $controller->buscarPnfs();
        break;
    
    // ─── TUS CASOS DE NÚCLEOS Y PNFS BIEN UBICADOS CON SEGURIDAD ───
    case "registrar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarNucleo();
        break;
    case "actualizar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->actualizarNucleo();
        break;
    case "eliminar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarNucleo();
        break;
    case "registrar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarPnf();
        break;
    case "actualizar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->actualizarPnf();
        break;
    case "eliminar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarPnf();
        break;
    case "registrar_oferta":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarOferta();
        break;
    case "eliminar_oferta":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarOferta();
        break;
    case "obtener_pnfs_por_nucleo":
        $controllerOferta->obtenerPnfsPorNucleo();
        break;
}*/

$rutasVistas = [
    "login"          => "showLogin",
    "perfil"         => "showPerfil",
    "usuarios"       => "showUsuarios",
    "consultas"      => "showConsultas",
    "configuracion"  => "showConfiguracion",
    "oferta"         => "showOferta",
    "sedes"          => "showSedes"
];



include_once __DIR__."/../app/permisos/permisos.php";

if (file_exists($paginaMostrar) && isset($rutasVistas[$paginaActual])) {
    $metodoController = $rutasVistas[$paginaActual];
    $viewController->$metodoController($partesRuta);
} else {
    include __DIR__."/../app/views/404.php";
}

/*switch($paginaActual) {
    case "perfil": 
    case "usuarios":
    case "consultas":
    case "configuracion":
    case "sesion":
    case "oferta": 
    case "sedes-carreras": 
        include __DIR__."/../app/views/perfil.php";
        break;

    default: 
        include __DIR__."/../app/views/login.php";
        break;
}*/

$rawUri = $_SERVER['REQUEST_URI'];
$cleanPath = parse_url($rawUri, PHP_URL_PATH);
$currentPage = trim($cleanPath, '/');

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
?>
