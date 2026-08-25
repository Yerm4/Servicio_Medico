<?php
use app\controller\Controller;
if ($_SESSION["cedula"]) {
    $controller = new Controller($pdo);
    $usuariosEncontrados = [];
    if ($tieneGestionarUsuarios) {
        $usuariosEncontrados = $controller->consultar();
    }
} else {
    header("Location: login");
}
$inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];
unset($_SESSION['inputs']);

$roles = [];
$permisos = [];
$rolePermMap = [];
if ($tieneGestionarRolesPermisos) {
    $roles = $userModel->obtenerRoles();
    $permisos = $userModel->obtenerPermisos();
    $rolesPermisos = $userModel->obtenerRolesPermisos();
    foreach ($rolesPermisos as $rp) {
        $rolePermMap[$rp['id_rol']][$rp['id_permiso']] = true;
    }
}
$condicionesRegistradas = [];
if (($paginaActual === 'condiciones' || $paginaActual === 'configuracion') && $tieneGestionarCondiciones) {
    $condicionesRegistradas = (new \app\model\Condicion($pdo))->consultarCondiciones();
}
$consultasRecientes = [];
$misConsultas = [];
$consultaModel = new \app\model\Consulta($pdo);

if ($paginaActual === 'consultas' && $tieneVerConsultas) {
    $consultasRecientes = $consultaModel->obtenerConsultasRecientes(20);
}

$stats = [];
$consultasRecientesDashboard = [];
if ($paginaActual === 'perfil') {
    if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos) {
        $misConsultas = $consultaModel->obtenerConsultasPorPaciente($_SESSION["cedula"]);
    } else {
        $stats = $consultaModel->obtenerEstadisticasDashboard();
        $consultasRecientesDashboard = $consultaModel->obtenerConsultasRecientes(5);
    }
}


$nucleos = [];
$pnfs = [];
$ofertas = [];
if ($paginaActual === 'oferta' || $paginaActual === 'sedes-carreras' || $paginaActual === 'usuarios') {
    $modeloOfertas = new \app\model\NucleoPNF($pdo);
    $nucleos = $modeloOfertas->obtenerNucleos();
    $pnfs = $modeloOfertas->obtenerPNFS();
    if ($paginaActual === 'oferta') {
        $ofertas = $modeloOfertas->obtenerOfertasActivas();
    }
}
?>
<main class="perfil">    
        <?php include_once __DIR__."/sidebar.php" ?>
        <section class="section-1 section-1--perfil">
    
<?php if ($tieneGestionarOferta): // ??? ?>
    <?php include_once __DIR__."/modal/modalRegistrarPNF.php" ?>
    <?php include_once __DIR__."/modal/modalActualizarPNF.php" ?>
    <?php include_once __DIR__."/modal/modalRegistrarNucleo.php" ?>
    <?php include_once __DIR__."/modal/modalActualizarNucleo.php" ?>
    <?php include_once __DIR__."/modal/modalRegistrarOferta.php" ?>
<?php endif; ?>
            
            <div class="buscador-caja">
                <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
                <input type="text" id="inputBuscarUsuario" placeholder="Buscar por cédula o nombre" class="action-card__input input-margin-bottom" autocomplete="off">
                <?php endif; ?>
                <?php if ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
                <input type="text" id="inputBuscarConsulta" placeholder="Buscar consulta por paciente, médico, cédula o motivo" class="action-card__input input-buscar-consulta" autocomplete="off">
                <?php endif; ?>
                
                <div class="section-1__box transition" id="section-1-box">
                    <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
                        <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
                    <?php elseif ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
                        <div class="box-iniciar-consulta" style="display: flex; gap: 10px;">
                            <?php if ($tieneRealizarConsulta): ?>
                                <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal btn-iniciar-consulta" href="#">Iniciar consulta</a>
                            <?php endif; ?>
                            <?php if ($tieneGenerarReportes): ?>
                                <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#" style="background-color: #0284c7; width: fit-content; text-align: center;">Generar Reporte</a>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($paginaActual === 'configuracion' && ($tieneGestionarRolesPermisos || $tieneGestionarCondiciones)): ?>
                        <span class="texto-configuracion-sistema">Configuración del Sistema</span>
                    <?php elseif ($paginaActual === 'sesion'): ?>
                        <a href="logout" class="link-cerrar-sesion">Cerrar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
            
            

            <?php if($paginaActual === 'usuarios' && $tieneGestionarUsuarios && $usuariosEncontrados): ?>

            <table id="tablaRegistros">
                <thead>
                <tr>
                    <th class="tabla-usuarios__title">Cedula</th>
                    <th class="tabla-usuarios__title">Nombre</th>
                    <th class="tabla-usuarios__title">Apellido</th>
                    <th class="tabla-usuarios__title">Tipo</th>
                    <th class="tabla-usuarios__title">Edad</th>
                    <th class="tabla-usuarios__title">Telefono</th>
                    <th class="tabla-usuarios__title"></th>
                    <th class="tabla-usuarios__title"></th>
                    <th class="tabla-usuarios__title"></th>
                </tr>
                </thead>
                <tbody id="cuerpoTablaUsuarios">
                <?php foreach ($usuariosEncontrados as $registro): ?>
                    <tr>
                    <td class="tabla-usuarios__desc"> <?=e($registro["cedula"])?></td>
                    <td class="tabla-usuarios__desc"> 
                        <div class="tabla__elipsis">
                            <?=e($registro["nombre"])?>
                        </div>
                    </td>
                    <td class="tabla-usuarios__desc">
                        <div class="tabla__elipsis">
                        <?=e($registro["apellido"])?>
                        </div>
                    </td>
                    <td class="tabla-usuarios__desc"><?= e($registro["nombre_tipo"] ?? ($registro["tipo"] === 0 ? "Estudiante" : "Docente")) ?></td>
                    <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"])?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["tlfprincipal"])?></td>
                    <td class="tabla-usuarios__desc"> 
                        <button class="consultar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">
                            <svg style="pointer-events:none" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="13" r="2" stroke="#000000" stroke-linejoin="round"/>
                                <path d="M12 7.5C7.69517 7.5 4.47617 11.0833 3.39473 12.4653C3.14595 12.7832 3.14595 13.2168 3.39473 13.5347C4.47617 14.9167 7.69517 18.5 12 18.5C16.3048 18.5 19.5238 14.9167 20.6053 13.5347C20.8541 13.2168 20.8541 12.7832 20.6053 12.4653C19.5238 11.0833 16.3048 7.5 12 7.5Z" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </td>
                    <td class="tabla-usuarios__desc"> 
                        <button class="editar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">
                            <svg style="pointer-events:none" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000000"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000000"/>
                            </svg>
                        </button>
                    </td>
                    <td class="tabla-usuarios__desc"> 
                        <button class="eliminar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">
                            <svg style="pointer-events:none" width="30" height="30" viewBox="0 0 512 512" fill="#000000" xmlns="http://www.w3.org/2000/svg">
                                <path d="M316.325 44.522V0H195.68l-.003 44.522H61.217v87.982h21.616c3.975 75.409 20.548 350.983 21.319 363.803L105.097 512h301.806l.944-15.694c.771-12.821 17.345-288.394 21.319-363.803h21.616V44.522H316.325zM229.069 33.391h53.866v11.13h-53.866V33.391zM375.458 478.609H136.542c-3.633-60.548-16.681-278.597-20.27-346.105h279.456c-3.588 67.503-16.637 285.551-20.27 346.105zM417.391 99.112H94.609V77.913h322.783v21.199z"/>
                                <path d="M239.304 167.947h33.391v280.031h-33.391z"/>
                                <path d="M160.292 168.19h33.39v279.952h-33.39z" transform="matrix(.9986 -.0521 .0521 .9986 -15.8157 9.64)"/>
                                <path d="M195.052 291.462h279.952v33.39H195.052z" transform="matrix(.0521 -.9986 .9986 .0521 9.8344 626.6741)"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                <?php endforeach?>
                </tbody>
            </table>

            <?php endif?>

            <?php if ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
                <div class="contenedor-tabla-consultas">
                    <h3 class="titulo-tabla-consultas">Consultas Recientes</h3>
                    <table id="tablaRegistrosConsultas" class="tabla-consultas">
                        <thead>
                            <tr class="tr-head-consultas">
                                <th class="th-consultas">Fecha</th>
                                <th class="th-consultas">Paciente</th>
                                <th class="th-consultas">Médico</th>
                                <th class="th-consultas">Motivo</th>
                                <th class="th-consultas">Síntomas</th>
                                <th class="th-consultas">Diagnóstico (CIE-10)</th>
                                <th class="th-consultas">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaConsultas">
                            <?php if (!empty($consultasRecientes)): ?>
                                <?php foreach ($consultasRecientes as $c): ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-consultas-nowrap"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
                                        <td class="td-consultas">
                                            <strong><?= e(($c['paciente_nombre'] ?? '') . ' ' . ($c['paciente_apellido'] ?? '')) ?></strong>
                                            <div class="td-paciente-sub">C.I. <?= e($c['id_usuario']) ?></div>
                                        </td>
                                        <td class="td-consultas"><?= e(($c['medico_nombre'] ?? '') . ' ' . ($c['medico_apellido'] ?? '')) ?></td>
                                        <td class="td-consultas"><?= e($c['motivo_de_visita']) ?></td>
                                        <td class="td-consultas">
                                            <?= !empty($c['sintomas']) ? e(implode(', ', $c['sintomas'])) : '<span class="sintomas-ninguno">Ninguno</span>' ?>
                                        </td>
                                        <td class="td-consultas">
                                            <?php if (!empty($c['diagnosticos'])): ?>
                                                <?php foreach ($c['diagnosticos'] as $diag): ?>
                                                    <div class="diagnostico-item-tabla">
                                                        <strong class="diagnostico-codigo"><?= e($diag['codigo_icd_diagnostico']) ?></strong> - <?= e($diag['patologia'] ?? 'Sin detalle') ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="sintomas-ninguno">Sin diagnóstico</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="td-acciones-btn">
                                            <button class="ver-detalles-consulta action-card__button btn-detalles-consulta" data-id="<?= e($c['id']) ?>">Ver detalles</button>
                                            <?php if ($tieneModificarConsulta): ?>
                                                <button class="editar-consulta action-card__button" data-id="<?= e($c['id']) ?>">Actualizar</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="td-tabla-vacia">No hay ninguna consulta asociada a ese usuario.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="contenedor-btn-cargar">
                        <button id="btnCargarMasConsultas" class="action-card__button btn-cargar-mas">Cargar más</button>
                    </div>
                </div>
            <?php endif; ?>

            

            
                
                <?php if (!empty($_SESSION["registro_msg"])): ?>
                    <div class="notification-banner notification-banner--<?= $_SESSION["registro_status"] ?>">
                        <p><strong><?php echo e($_SESSION["registro_msg"]); unset($_SESSION["registro_msg"]); ?></strong></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php if ($tieneGestionarUsuarios): ?>
            <?php include_once __DIR__."/modal/modalRegistrarUsuario.php" ?>
            <?php include_once __DIR__."/modal/modalActualizarUsuario.php" ?>
            <?php include_once __DIR__."/modal/modalDetallesUsuario.php" ?>
        <?php endif; ?>
        <?php if ($tieneVerConsultas): ?>
            <?php include_once __DIR__."/modal/modalRegistrarConsulta.php" ?>
            <?php include_once __DIR__."/modal/modalActualizarConsulta.php" ?>
            <?php include_once __DIR__."/modal/modalBuscarConsulta.php" ?>
        <?php endif; ?>
        <?php if ($tieneGenerarReportes): ?>
        
            <?php include_once __DIR__."/modal/modalReporteMorbilidad.php" ?>
        <?php endif; ?>
        <?php if ($tieneGestionarRolesPermisos): ?>
            <?php include_once __DIR__."/modal/modalEditarRol.php" ?>
        <?php endif; ?>
        <?php if ($tieneGestionarCondiciones): ?>
            <?php include_once __DIR__."/modal/modalRegistrarCondicion.php" ?>
            <?php include_once __DIR__."/modal/modalEditarCondicion.php" ?>
        <?php endif; ?>
        <script>
            const ES_MEDICO_O_DIRECTOR = <?= $tieneModificarConsulta ? 'true' : 'false' ?>;
        </script>
        <?php include_once __DIR__."/modal/modalVerDetallesConsulta.php" ?>
    </main>
    <footer>
        <script src="assets/script/append.js" defer></script>
        <script src="assets/script/eliminar.js" defer></script>
        <script src="assets/script/gestion.js" defer></script>
        <script src="assets/script/gestionpnfnucleo.js" defer></script>
        <script src="assets/script/gestionoferta.js" defer></script>
    </footer>
</body>
</html>