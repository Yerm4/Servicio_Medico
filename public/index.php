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
use app\controller\ConsultaController;

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

if (isset($_GET["ruta"]) && $_GET["ruta"] !== '') $ruta = trim($_GET["ruta"], "/");
else $ruta = "login";

$partesRuta = explode("/", $ruta);
$paginaActual = $partesRuta[0];
if ($paginaActual === "logout") {
    session_unset();
    session_destroy();
    header("Location: login"); 
    exit();
}

if (preg_match('#(api/.*)$#', $ruta, $coincidenciaApi)) {
    $endpoint = $coincidenciaApi[1];
    $apiController = new ApiController($endpoint);
}

$rutasVistas = [
    "login"          => "showLogin",
    "perfil"         => "showPerfil",
    "usuarios"       => "showUsuario",
    "consultas"      => "showConsultas",
    "configuracion"  => "showConfiguracion"
];

include_once __DIR__."/../app/permisos/permisos.php";

$formAccion = $_POST["form"] ?? null;
if ($formAccion && $_SERVER["REQUEST_METHOD"] === "POST") {
    $cConsulta = new ConsultaController();
    switch ($formAccion) {
        case "registro_consulta":
            $cConsulta->registroConsulta();
            exit();
        case "actualizar_consulta":
            $cConsulta->actualizarConsulta();
            exit();
        case "generar_reporte_morbilidad":
            $cConsulta->generarReporteMorbilidad();
            exit();
        case "guardar_roles_permisos":
            $cConsulta->guardarRolesPermisos();
            #header("Location: configuracion");
            exit();
        case "registrar_rol":
            $cConsulta->registrarRol();
            #header("Location: configuracion");
            exit();
        case "actualizar_rol":
            $cConsulta->actualizarRol();
            #header("Location: configuracion");
            exit();
        case "eliminar_rol":
            $cConsulta->eliminarRol();
            #header("Location: configuracion");
            exit();
        case "guardar_configuracion":
            $cConsulta->guardarConfiguracion();
            #header("Location: configuracion");
            exit();
        case "registrar_condicion":
            $cConsulta->registrarCondicion();
            #header("Location: configuracion");
            exit();
        case "actualizar_condicion":
            $cConsulta->actualizarCondicion();
            #header("Location: configuracion");
            exit();
        case "eliminar_condicion":
            $cConsulta->eliminarCondicion();
            #header("Location: configuracion");
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