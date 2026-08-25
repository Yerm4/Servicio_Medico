<?php
namespace app\controller;

use app\controller\Controller;
use app\controller\PacienteController;
use app\controller\ConsultaController;
use app\controller\CondicionController;
use app\controller\NucleoPNFController;
use app\model\NucleoPNF;
use app\model\Consulta;
use app\model\Condicion;
use app\config\Config;
use app\model\Usuario;

class ViewController {

    private $pdo;
    public function __construct($conexion){
        $this->pdo = $conexion;
    }

    private function auth() {
        
    }

    public function showLogin() {
        $modeloOfertas = new NucleoPNF($this->pdo);
        $nucleos = $modeloOfertas->obtenerNucleos();
        $pnfs = $modeloOfertas->obtenerPNFS();
        $userModel = new Usuario($this->pdo);
        $tipos = $userModel->obtenerTipos();

        if ($pnfs && $nucleos) {
            include_once __DIR__."/../views/login.php";
        }
    }

    public function showPerfil() {

        global $tieneGestionarUsuarios, $tieneVerConsultas, $tieneGestionarRolesPermisos, $tieneModificarConsulta;
        $this->auth();
        $consultaModel = new Consulta($this->pdo);
        $stats = [];
        $consultasRecientesDashboard = [];
        $misConsultas = [];

        // 2. Lógica del Controlador: Buscar datos según el tipo de usuario
        if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos) {
            // Es un paciente
            $misConsultas = $consultaModel->obtenerConsultasPorPaciente($_SESSION["cedula"]);
        } else {
            // Es personal médico / administrativo
            $stats = $consultaModel->obtenerEstadisticasDashboard();
            $consultasRecientesDashboard = $consultaModel->obtenerConsultasRecientes(5);
        }

        $paginaActual = 'perfil';
        $inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];
        unset($_SESSION['inputs']);
        
        // 3. Renderizamos la Vista (Corregimos la ruta, quitando la carpeta 'dashboard/')
        include __DIR__ . "/../views/perfil.php";
    }

    public function showUsuario($userModel = null, $permisos = null) {

        $controller = new Controller($this->pdo);
        $usuariosEncontrados = $controller->consultar();

        include __DIR__ . "/../views/usuario.php";
    }

    public function showConsultas($userModel, $permisos) {
        extract($permisos);
        if (!$tieneVerConsultas) {
            header("Location: perfil");
            exit();
        }

        $consultaModel = new Consulta($this->pdo);
        $consultasRecientes = $consultaModel->obtenerConsultasRecientes(20);

        $paginaActual = 'consultas';
        include __DIR__ . "/../views/consultas.php";
    }

    public function showConfiguracion($userModel, $permisos) {
        extract($permisos);
        $roles = [];
        $permisosLista = [];
        $rolePermMap = [];
        $condicionesRegistradas = [];

        if ($tieneGestionarRolesPermisos) {
            $roles = $userModel->obtenerRoles();
            $permisosLista = $userModel->obtenerPermisos();
            $rolesPermisos = $userModel->obtenerRolesPermisos();
            foreach ($rolesPermisos as $rp) {
                $rolePermMap[$rp['id_rol']][$rp['id_permiso']] = true;
            }
        }

        if ($tieneGestionarCondiciones) {
            $condicionesRegistradas = (new Condicion($this->pdo))->consultarCondiciones();
        }

        $paginaActual = 'configuracion';
        include __DIR__ . "/../views/configuracion.php";
    }

    public function showOferta($userModel, $permisos) {
        extract($permisos);
        $modeloOfertas = new NucleoPNF($this->pdo);
        $ofertas = $modeloOfertas->obtenerOfertasActivas();

        $paginaActual = 'oferta';
        include __DIR__ . "/../views/oferta.php";
    }

    public function showSedes() {
        include_once __DIR__."/../views/sedes.php";
    }
}