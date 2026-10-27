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
use app\model\Usuario;

class ViewController {

    private function auth() {
        
    }

    public function showLogin() {
        
        if (!empty($_SESSION["cedula"])) header("Location: perfil");
        
        $modeloOfertas = new NucleoPNF();
        $nucleos = $modeloOfertas->obtenerNucleos();
        $pnfs = $modeloOfertas->obtenerPNFS();
        $userModel = new Usuario();
        $tipos = $userModel->obtenerTipos();

        if ($pnfs && $nucleos) {
            include_once __DIR__."/../views/login.php";
        }
    }

    public function showPerfil() {

        global $tieneGestionarUsuarios, $tieneVerConsultas, $tieneGestionarRolesPermisos, $tieneModificarConsulta, $tieneGenerarReportes, $tieneRealizarConsulta;
        $this->auth();
        $consultaModel = new Consulta();
        $stats = [];
        $consultasRecientesDashboard = [];
        $misConsultas = [];
        $misCondiciones = [];

        if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos) {
            $misConsultas = $consultaModel->obtenerConsultasPorPaciente($_SESSION["cedula"]);
            $misCondiciones = $consultaModel->obtenerCondicionesPaciente($_SESSION["cedula"]);
        } else {
            $stats = $consultaModel->obtenerEstadisticasDashboard();
            $consultasRecientesDashboard = $consultaModel->obtenerConsultasRecientes(5);
        }

        $userModel = new Usuario();
        $tipos = $userModel->obtenerTipos();

        $inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];
        unset($_SESSION['inputs']);
        $paginaActual = 'perfil';
        include __DIR__ . "/../views/perfil.php";
    }

    public function showUsuario($userModel = null, $permisos = null) {

        $pnfModel = new NucleoPNF();
        $userModel = new Usuario();

        $nucleos = $pnfModel->obtenerNucleos();
        $pnfs = $pnfModel->obtenerPNFS();
        
        $tipos = $userModel->obtenerTipos();
        $data = $userModel->consultarUsuarios();
        $paginaActual = 'usuarios';
        if ($data["status"] === "ok") {
            $usuariosEncontrados = $data["data"];
            include __DIR__ . "/../views/usuarios.php";
        } else {
            include __DIR__ . "/../views/404.php";
        }
    }

    public function showConsultas($userModel = null, $permisos = null) {
        global $tieneGestionarUsuarios, $tieneVerConsultas, $tieneGestionarRolesPermisos, $tieneModificarConsulta, $tieneGenerarReportes, $tieneRealizarConsulta;
        $pnfModel = new NucleoPNF();
        $userModel = new Usuario();
        $consultaModel = new Consulta();

        $nucleos = $pnfModel->obtenerNucleos();
        $pnfs = $pnfModel->obtenerPNFS();
        
        $tipos = $userModel->obtenerTipos();
        $data = $userModel->consultarUsuarios();
        $consultasRecientes = $consultaModel->obtenerConsultasRecientes(20);
        $paginaActual = 'consultas';
        include __DIR__ . "/../views/consultas.php";
    }

    public function showConfiguracion($partesRuta = null) {
        global $tieneGestionarRolesPermisos, $tieneGestionarCondiciones, $tieneGestionarOferta;
        $userModel = new Usuario();
        $roles = [];
        $permisos = [];
        $rolePermMap = [];
        $condicionesRegistradas = [];
        
        if ($tieneGestionarRolesPermisos) {
            $roles = $userModel->obtenerRoles();
            $permisos = $userModel->obtenerPermisos();
            $rolesPermisos = $userModel->obtenerRolesPermisos();
            foreach ($rolesPermisos as $rp) {
                $rolePermMap[$rp['id_rol']][$rp['id_permiso']] = true;
            }
        }

        if ($tieneGestionarCondiciones) {
            $condicionesRegistradas = (new Condicion())->consultarCondiciones();
        }

        $modeloOfertas = new NucleoPNF();
        $nucleos = $modeloOfertas->obtenerNucleos();
        $pnfs = $modeloOfertas->obtenerPNFS();
        $ofertas = $modeloOfertas->obtenerOfertasActivas();

        $paginaActual = 'configuracion';
        include __DIR__ . "/../views/configuracion.php";
    }
}