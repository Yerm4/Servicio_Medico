<?php
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $file = __DIR__ . $path;
    if ($path !== '/' && !empty($path) && $path !== '/index.php' && is_file($file)) {
        return false;
    }
}

session_start();
require_once __DIR__."/../vendor/autoload.php";

use app\controller\ViewController;
use app\controller\ApiController;

if (file_exists(__DIR__ . '/.env')) $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); 
else $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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

$viewController = new ViewController();  
$apiController = new ApiController();

$method = !empty($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
$rutasApi = require __DIR__."/../app/api.php";

if (isset($_GET["ruta"]) && $_GET["ruta"] !== '') {
    $ruta = trim($_GET["ruta"], "/");
} else {
    $ruta = "login";
}

$partesRuta = explode("/", $ruta);
$paginaActual = $partesRuta[0];

if ($paginaActual === "logout") {
    session_unset();
    session_destroy();
    header("Location: login"); 
    exit();
}

$rutaNormalizada = $ruta;
if (preg_match('#(api/.*)$#', $ruta, $coincidenciaApi)) {
    $rutaNormalizada = $coincidenciaApi[1];
}

$metodoEncontrado = null;
$parametros = [];

if (is_array($rutasApi) && isset($rutasApi[$method])) {
    foreach ($rutasApi[$method] as $patronRuta => $metodo) {
        $patronRegex = "#^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $patronRuta) . "$#";
        if (preg_match($patronRegex, $rutaNormalizada, $coincidencias) || preg_match($patronRegex, $ruta, $coincidencias)) {
            $metodoEncontrado = $metodo;
            array_shift($coincidencias);
            $parametros = $coincidencias;
            break;
        }
    }
}

if ($metodoEncontrado) {
    header("Content-Type: application/json; charset=UTF-8");
    $apiController = new ApiController();

    if (method_exists($apiController, $metodoEncontrado)) {
        $apiController->$metodoEncontrado(...$parametros);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "El método del controlador no existe"]);
        exit();
    }
}

if (str_starts_with($rutaNormalizada, "api/") || str_starts_with($ruta, "api/")) { 
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(404); 
    echo json_encode([ 
        "status" => "error", 
        "message" => "La ruta: " . $rutaNormalizada . " no existe" 
    ]); 
    exit(); 
}
$rutasVistas = [
    "login"          => "showLogin",
    "perfil"         => "showPerfil",
    "usuarios"       => "showUsuario",
    "consultas"      => "showConsultas",
    "configuracion"  => "showConfiguracion"
];

$objeto = new ApiController();

include_once __DIR__."/../app/permisos/permisos.php";

$formAccion = $_POST["form"] ?? null;
if ($formAccion && $_SERVER["REQUEST_METHOD"] === "POST") {
    $apiCtrl = new ApiController();
    switch ($formAccion) {
        case "generar_reporte_morbilidad":
            $apiCtrl->generarReporteMorbilidad();
            exit();
        case "registro_consulta":
            $cConsulta = new \app\controller\ConsultaController();
            $cConsulta->registrar();
            exit();
        case "actualizar_consulta":
            $cConsulta = new \app\controller\ConsultaController();
            $cConsulta->actualizar();
            exit();
        case "guardar_roles_permisos":
            $apiCtrl->guardarRolesPermisos();
            header("Location: configuracion");
            exit();
        case "registrar_rol":
            $apiCtrl->registrarRol();
            header("Location: configuracion");
            exit();
        case "actualizar_rol":
            $apiCtrl->actualizarRol();
            header("Location: configuracion");
            exit();
        case "eliminar_rol":
            $apiCtrl->eliminarRol();
            header("Location: configuracion");
            exit();
        case "guardar_configuracion":
            $apiCtrl->guardarConfiguracion();
            header("Location: configuracion");
            exit();
        case "registrar_condicion":
            $apiCtrl->registrarCondicion();
            header("Location: configuracion");
            exit();
        case "actualizar_condicion":
            $apiCtrl->actualizarCondicion();
            header("Location: configuracion");
            exit();
        case "eliminar_condicion":
            $apiCtrl->eliminarCondicion();
            header("Location: configuracion");
            exit();
    }
}
$paginaMostrar = __DIR__."/../app/views/$paginaActual.php";

if (file_exists($paginaMostrar) && isset($rutasVistas[$paginaActual])) {
    $metodoController = $rutasVistas[$paginaActual];
    $viewController->$metodoController($partesRuta); 
} else {
    include __DIR__."/../app/views/404.php";
}