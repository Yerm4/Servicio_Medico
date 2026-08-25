<?php
use app\controller\Controller;
// Validar autenticación
if (empty($_SESSION["cedula"])) {
    header("Location: login");
    exit;
}

// Inicialización de variables generales
$inputs = $_SESSION['inputs'] ?? [];
unset($_SESSION['inputs']);

$usuariosEncontrados = [];
$roles = [];
$permisos = [];
$rolePermMap = [];
$condicionesRegistradas = [];
$consultasRecientes = [];
$misConsultas = [];
$stats = [];
$consultasRecientesDashboard = [];
$nucleos = [];
$pnfs = [];
$ofertas = [];

// Carga de datos según vista activa y permisos
if ($tieneGestionarUsuarios) {
    $controller = new \app\controller\Controller($pdo);
    $usuariosEncontrados = $controller->consultar();
}

if ($tieneGestionarRolesPermisos) {
    $roles = $userModel->obtenerRoles();
    $permisos = $userModel->obtenerPermisos();
    $rolesPermisos = $userModel->obtenerRolesPermisos();
    foreach ($rolesPermisos as $rp) {
        $rolePermMap[$rp['id_rol']][$rp['id_permiso']] = true;
    }
}

if (($paginaActual === 'condiciones' || $paginaActual === 'configuracion') && $tieneGestionarCondiciones) {
    $condicionesRegistradas = (new \app\model\Condicion($pdo))->consultarCondiciones();
}

$consultaModel = new \app\model\Consulta($pdo);

if ($paginaActual === 'consultas' && $tieneVerConsultas) {
    $consultasRecientes = $consultaModel->obtenerConsultasRecientes(20);
}

if ($paginaActual === 'perfil') {
    if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos) {
        $misConsultas = $consultaModel->obtenerConsultasPorPaciente($_SESSION["cedula"]);
    } else {
        $stats = $consultaModel->obtenerEstadisticasDashboard();
        $consultasRecientesDashboard = $consultaModel->obtenerConsultasRecientes(5);
    }
}

if (in_array($paginaActual, ['oferta', 'sedes-carreras', 'usuarios'])) {
    $modeloOfertas = new \app\model\NucleoPNF($pdo);
    $nucleos = $modeloOfertas->obtenerNucleos();
    $pnfs = $modeloOfertas->obtenerPNFS();
    if ($paginaActual === 'oferta') {
        $ofertas = $modeloOfertas->obtenerOfertasActivas();
    }
}
?>
<main class="perfil">    
<main class="perfil">
    <?php include_once __DIR__ . '/../components/sidebar.php'; ?>

    <section class="section-1 section-1--perfil">
        <?php include_once __DIR__ . '/../components/action_bar.php'; ?>

        <?php
        // Enrutador de vistas dinámicas
        switch ($paginaActual) {
            case 'sedes-carreras':
                include_once __DIR__ . '/../pages/sedes_carreras.php';
                break;
            case 'oferta':
                include_once __DIR__ . '/../pages/ofertas.php';
                break;
            case 'usuarios':
                include_once __DIR__ . '/../pages/usuarios.php';
                break;
            case 'consultas':
                include_once __DIR__ . '/../pages/consultas.php';
                break;
            case 'perfil':
                include_once __DIR__ . '/../pages/perfil.php';
                break;
            case 'configuracion':
                include_once __DIR__ . '/../pages/configuracion.php';
                break;
        }
        ?>

        <?php if (!empty($_SESSION["registro_msg"])): ?>
            <div class="notification-banner notification-banner--<?= $_SESSION["registro_status"] ?>">
                <p><strong><?= e($_SESSION["registro_msg"]); unset($_SESSION["registro_msg"]); ?></strong></p>
            </div>
        <?php endif; ?>
    </section>

    <?php include_once __DIR__ . '/../components/modals_container.php'; ?>
</main>

<footer>
    <script>const ES_MEDICO_O_DIRECTOR = <?= $tieneModificarConsulta ? 'true' : 'false' ?>;</script>
    <script src="assets/script/append.js" defer></script>
    <script src="assets/script/eliminar.js" defer></script>
    <script src="assets/script/gestion.js" defer></script>
    <script src="assets/script/gestionpnfnucleo.js" defer></script>
    <script src="assets/script/gestionoferta.js" defer></script>
</footer>