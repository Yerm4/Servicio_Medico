<?php
session_start();
require_once __DIR__."/../vendor/autoload.php";

use app\controller\ViewController;
use app\controller\ApiController;
use app\config\Config;

$pdo = Config::conexion(); 
$viewController = new ViewController($pdo);  
$apiController = new ApiController($pdo);


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

$method = !empty($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";

$rutasApi = [
    "GET" => [
        "api/users/buscar"                  => "buscarUsuario",
        "api/users/cedula"                  => "obtenerUsuario",
        "api/consultas"                     => "buscarConsultas",
        "api/consultas/detalle"             => "obtenerConsulta",
        "api/consultas/reporte-morbilidad"  => "generarReporteMorbilidad",
        "api/pnfs"                          => "buscarPnfs",
        "api/nucleos/pnfs/{id}"                  => "obtenerPnfsPorNucleo"
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

if ($paginaActual === "logout") {
    session_unset();
    session_destroy();
    header("Location: login"); 
    exit();
}

$metodoEncontrado = null;
$parametros = [];

if (isset($rutasApi[$method])) {
    foreach ($rutasApi[$method] as $patronRuta => $metodo) {
        $patronRegex = "#^" . preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $patronRuta) . "$#";
        if (preg_match($patronRegex, $ruta, $coincidencias)) {
            $metodoEncontrado = $metodo;
            array_shift($coincidencias);
            $parametros = $coincidencias;
            break;
        }
    }
}

if ($metodoEncontrado) {
    header("Content-Type: application/json; charset=UTF-8");
    $apiController = new ApiController($pdo);

    if (method_exists($apiController, $metodoEncontrado)) {
        $apiController->$metodoEncontrado(...$parametros);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "El método del controlador no existe"]);
        exit();
    }
}

if (str_starts_with($ruta, "api/")) { 
    header("Content-Type: application/json; 
    charset=UTF-8"); http_response_code(404); 
    echo json_encode([ 
        "status" => "error", 
        "message" => "La ruta: ".$ruta." no existe" 
        ]); 
    exit(); 
}

$rutasVistas = [
    "login"          => "showLogin",
    "perfil"         => "showPerfil",
    "usuario"        => "showUsuario",
    "consultas"      => "showConsultas",
    "configuracion"  => "showConfiguracion",
    "oferta"         => "showOferta",
    "sedes"          => "showSedes"
];

include_once __DIR__."/../app/permisos/permisos.php";
$paginaMostrar = __DIR__."/../app/views/$paginaActual.php";

if (file_exists($paginaMostrar) && isset($rutasVistas[$paginaActual])) {
    $metodoController = $rutasVistas[$paginaActual];
    $viewController->$metodoController($partesRuta);
} else {
    include __DIR__."/../app/views/404.php";
}

$rawUri = $_SERVER['REQUEST_URI'];
$cleanPath = parse_url($rawUri, PHP_URL_PATH);
$currentPage = trim($cleanPath, '/');
?>
