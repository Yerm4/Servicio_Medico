<?php
require_once __DIR__."/../vendor/autoload.php";
use app\controller\Controller;
use app\controller\PacienteController;
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

/*session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Lax'
]);*/
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit("CSRF token validation failed");
    }

    $form = isset($_POST["form"]) ? $_POST["form"] : '';
    switch($form) {
        case "registro":
            $controller->registrar();
            break;

        case "registro_paciente":
            $controllerPaciente->Registrar();    
            break;
        case "login":
            $controller->login();
            break;
    }
}

$ruta = isset($_GET["ruta"]) ? trim($_GET["ruta"], "/") : "login";
$partesRuta = explode("/", $ruta);
$paginaActual = $partesRuta[0];

include __DIR__."/../app/views/header.php";

switch($paginaActual) {
    case "login":
        include __DIR__."/../app/views/login.php";
        break;

    case "registrar": 
        include __DIR__."/../app/views/registropaciente.php";
        break;

    case "perfil": 
        include __DIR__."/../app/views/perfil.php";
        break;

    case "form";
        include __DIR__."/../app/views/formModelo.php";
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
?>
