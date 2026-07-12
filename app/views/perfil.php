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
        <aside class="side-menu">
        <h1>Menu</h1>
            <hr>
            <a href="perfil" id="inicio" class="<?= $paginaActual === 'perfil' ? 'focus' : '' ?>">Inicio</a>
            <?php if ($tieneGestionarUsuarios): ?>
            <a href="usuarios" id="usuario" class="<?= $paginaActual === 'usuarios' ? 'focus' : '' ?>">Usuarios</a>
            <?php endif; ?>
            <?php if ($tieneVerConsultas): ?>
            <a href="consultas" id="consulta" class="<?= $paginaActual === 'consultas' ? 'focus' : '' ?>">Consultas</a>
            <?php endif; ?>
            <?php if ($tieneGestionarRolesPermisos || $tieneGestionarCondiciones): ?>
            <a href="configuracion" id="configuracion" class="<?= $paginaActual === 'configuracion' ? 'focus' : '' ?>">Configuración</a>
            <?php endif; ?>
            <?php if ($tieneGestionarOferta):?>
        <a href="sedes-carreras" id="sedes-carreras" class="<?= $paginaActual === 'sedes-carreras' ? 'focus' : '' ?>">Nucleos y PNFS</a>
        <a href="oferta" id="oferta" class="<?= $paginaActual === 'oferta' ? 'focus' : '' ?>">Ofertas Academicas</a>
    <?php endif; ?>
        </aside>
        <section class="section-1 section-1--perfil">
    <?php if ($paginaActual === 'sedes-carreras'): ?>
    <div  id="sedes-carreras" class="contenedor-sedes-carreras" style="margin-top: 1.5rem; width: 100%; display: flex; flex-direction: column;">
        
        <div class="gestion-grid-superior" style="display: flex; flex-direction: column; width: 100%; gap: 2.5rem; margin-bottom: 2rem;">
            
            <div class="contenedor-tabla-consultas" style="width: 100%;">
                <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 class="titulo-tabla-consultas" style="margin: 0;">Núcleos</h3>
                    <a name="openModal" data-modal="modalRegistrarNucleo" class="action-card__button" href="#" style="width: fit-content; display: inline-block;">Registrar Nucleo</a>
                </div>
                        <div id="alert-container-nucleo" style="margin-bottom: 1rem;"></div>
                <table id="tablaNucleos" class="tabla-consultas" style="width: 100%;">
                    <thead>
                        <tr class="tr-head-consultas">
                            <th class="th-consultas" style="text-align: left;">ID</th>
                            <th class="th-consultas" style="text-align: left;">Nombre del Núcleo</th>
                            <th class="th-consultas" style="text-align: left;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaNucleos">
                        <?php 
    
                    if (!empty($nucleos)): 
                        $i = 1;
                        foreach ($nucleos as $n): 
                        ?>
                                <tr class="tr-body-consultas">
                                    <td class="td-consultas-nowrap" style="text-align: left;"><?= $i++ ?></td>
                                    <td class="td-consultas" style="text-align: left;"><strong><?= e($n['nombre_nucleo']) ?></strong></td>
                                    
                                    <td class="td-acciones-btn" style="text-align: left;">
                                        <div style="display: flex; gap: 10px; justify-content: flex-start; align-items: center;">
                                            <button class="action-card__button editar-nucleo" name="openModal" data-modal="modalActualizarNucleo" data-id="<?= e($n['id_nucleo']) ?>" data-nombre="<?= e($n['nombre_nucleo']) ?>" style="width: fit-content; padding: 0.5rem 1rem; cursor: pointer">Actualizar</button>

                                            <form method="POST" action="index.php" class="form-eliminar-nucleo" style="display: inline; width: fit-content; margin: 0;">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                                <input type="hidden" name="form" value="eliminar_nucleo">
                                                <input type="hidden" name="id_nucleo" value="<?= e($n['id_nucleo']) ?>">
                                                <button type="submit" class="action-card__button" style="background-color: #d9534f; width: fit-content; padding: 0.5rem 1rem; cursor: pointer;">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="td-tabla-vacia">No hay núcleos registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
            </div>
<div id="contenedor-tabla-dinamica">
    <div class="contenedor-tabla-consultas" style="width: 100%;">
        <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 class="titulo-tabla-consultas" style="margin: 0;">Programas de Formación Nacional (PNF)</h3>
            <a name="openModal" data-modal="modalRegistrarPNF" class="action-card__button" href="#" style="width: fit-content; display: inline-block; cursor: pointer">Registrar PNF</a>
        </div>
        
        <div id="alert-container-pnf" style="margin-bottom: 1rem;"></div>
        
        <table id="tablaPnfs" class="tabla-consultas" style="width: 100%;">
            <thead>
                <tr class="tr-head-consultas">
                    <th class="th-consultas" style="text-align: left;">ID</th>
                    <th class="th-consultas" style="text-align: left;">Nombre del PNF</th>
                    <th class="th-consultas" style="text-align: left;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-pnf-body">
                    <?php 
                if (!empty($pnfs)): 
                    $i = 1; 
                    foreach ($pnfs as $p): 
                    ?>
                        <tr class="tr-body-consultas">
                            <td class="td-consultas-nowrap" style="text-align: left;">
                                <?= $i++ ?>
                            </td>
                            
                            <td class="td-consultas" style="text-align: left;">
                                <strong><?= e($p['nombre_pnf']) ?></strong>
                            </td>
                            
                            <td class="td-acciones-btn" style="text-align: left;">
                                <div style="display: flex; gap: 10px; justify-content: flex-start; align-items: center;">
                                    
                                    <button class="action-card__button editar-pnf" name="openModal" data-modal="modalActualizarPNF" data-id="<?= e($p['id_pnf']) ?>" data-nombre="<?= e($p['nombre_pnf']) ?>" style="width: fit-content; padding: 0.5rem 1rem; cursor: pointer;">Actualizar</button>
                
                                    <form method="POST" action="index.php" class="form-eliminar-pnf" style="display: inline; width: fit-content; margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="form" value="eliminar_pnf">
                                        <input type="hidden" name="id_pnf" value="<?= e($p['id_pnf']) ?>">
                                        <button type="submit" class="action-card__button" style="background-color: #d9534f; width: fit-content; padding: 0.5rem 1rem; cursor: pointer;">Eliminar</button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="td-tabla-vacia">No hay PNFs registrados en el sistema.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
        <?php if ($paginaActual === 'oferta'): ?>
        <div class="contenedor-tabla-consultas" style="margin-top: 2rem; width: 100%;">
            <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 class="titulo-tabla-consultas" style="margin: 0;">Ofertas Académicas</h3>
                <a name="openModal" data-modal="modalRegistrarOferta" class="action-card__button" href="#"; style="width: fit-content; display: inline-block; cursor: pointer">Registrar Oferta Academica</a>
            </div>
                <div id="alert-container-oferta" style="margin-bottom: 1rem;"></div>
            <table id="tablaRegistrosOfertas" class="tabla-consultas">
                <thead>
                    <tr class="tr-head-consultas">
                        <th class="th-consultas">Núcleo</th>
                        <th class="th-consultas">Programa de Formación Nacional (PNF)</th>
                        <th class="th-consultas">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaOfertas">
                    <?php if (!empty($ofertas)): ?>
                        <?php foreach ($ofertas as $o): ?>
                            <tr class="tr-body-consultas">
                                <td class="td-consultas"><strong><?= e($o['nombre_nucleo']) ?></strong></td>
                                <td class="td-consultas"><?= e($o['nombre_pnf']) ?></td>
                                <td class="td-acciones-btn">
                                    <form method="POST" action="index.php";>
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="form" value="eliminar_oferta">
                                        <input type="hidden" name="id_nucleo" value="<?= e($o['id_nucleo']) ?>">
                                        <input type="hidden" name="id_pnf" value="<?= e($o['id_pnf']) ?>">
                                        <button type="submit" class="action-card__button" style="background-color: #d9534f; cursor: pointer;">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas actualmente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
<?php endif; ?>
<?php if ($tieneGestionarOferta):?>
    <dialog id="modalRegistrarPNF" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <?php include_once __DIR__."/modalRegistrarPNF.php" ?>
</dialog>
<dialog id="modalActualizarPNF" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <?php include_once __DIR__."/modalActualizarPNF.php" ?>
</dialog>
<dialog id="modalRegistrarNucleo" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <?php include_once __DIR__."/modalRegistrarNucleo.php" ?>
</dialog>
<dialog id="modalActualizarNucleo" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <?php include_once __DIR__."/modalActualizarNucleo.php" ?>
</dialog>
<dialog id="modalRegistrarOferta" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <?php include_once __DIR__."/modalRegistrarOferta.php" ?>
</dialog>
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
                </tr>
                </thead>
                <tbody id="cuerpoTablaUsuarios">
                <?php foreach ($usuariosEncontrados as $registro): ?>
                    <tr>
                    <td class="tabla-usuarios__desc"> <?=e($registro["cedula"])?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["nombre"])?></td>
                    <td class="tabla-usuarios__desc"><?=e($registro["apellido"])?></td>
                    <td class="tabla-usuarios__desc"><?= e($registro["nombre_tipo"] ?? ($registro["tipo"] === 0 ? "Estudiante" : "Docente")) ?></td>
                    <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"])?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["tlfprincipal"])?></td>
                    <td class="tabla-usuarios__desc"> <button class="editar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Actualizar</button></td>
                    <td class="tabla-usuarios__desc"> <button class="consultar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Detalles</button></td>
                    <td class="tabla-usuarios__desc"> <button class="eliminar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Eliminar</button></td>
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

            <?php if ($paginaActual === 'perfil'): ?>
                <?php if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos): ?>
                    <?php if (!empty($misConsultas)): ?>
                        <div class="contenedor-tabla-consultas">
                            <h3 class="titulo-tabla-consultas">Mi Historial Médico</h3>
                            <table class="tabla-consultas">
                                <thead>
                                    <tr class="tr-head-consultas">
                                        <th class="th-consultas">Fecha</th>
                                        <th class="th-consultas">Médico Tratante</th>
                                        <th class="th-consultas">Motivo</th>
                                        <th class="th-consultas">Síntomas</th>
                                        <th class="th-consultas">Diagnóstico</th>
                                        <th class="th-consultas">Tratamiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($misConsultas as $c): ?>
                                        <tr class="tr-body-consultas">
                                            <td class="td-consultas-nowrap"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
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
                                            <td class="td-consultas"><?= e($c['medicamento_suministrado'] ?: 'Ninguno') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="contenedor-historial-vacio">
                            <p class="texto-historial-vacio">No hay consultas médicas asociadas a este usuario.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="dashboard-container">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h3 class="titulo-configuracion-interna" style="margin: 0;">Panel de Inicio</h3>
                            <?php if ($tieneGenerarReportes): ?>
                                <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#" style="background-color: #0284c7; width: fit-content; text-align: center;">Generar Reporte de Morbilidad</a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="dashboard-stats-grid">
                            <div class="stat-card">
                                <div class="stat-card__number"><?= $stats['total_consultas'] ?></div>
                                <div class="stat-card__label">Consultas Realizadas</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card__number"><?= $stats['total_usuarios'] ?></div>
                                <div class="stat-card__label">Usuarios Registrados</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card__number"><?= $stats['total_condiciones'] ?></div>
                                <div class="stat-card__label">Condiciones Médicas</div>
                            </div>
                        </div>

                        <div class="contenedor-tabla-consultas">
                            <h3 class="titulo-tabla-consultas" style="text-align: left; margin-bottom: 15px;">Últimas Consultas Registradas</h3>
                            <?php if (empty($consultasRecientesDashboard)): ?>
                                <div class="contenedor-historial-vacio">
                                    <p class="texto-historial-vacio">No hay consultas médicas registradas recientemente.</p>
                                </div>
                            <?php else: ?>
                                <table class="tabla-consultas">
                                    <thead>
                                        <tr class="tr-head-consultas">
                                            <th class="th-consultas">Fecha</th>
                                            <th class="th-consultas">Paciente</th>
                                            <th class="th-consultas">Médico Tratante</th>
                                            <th class="th-consultas">Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($consultasRecientesDashboard as $c): ?>
                                            <tr class="tr-body-consultas">
                                                <td class="td-consultas-nowrap"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
                                                <td class="td-consultas"><strong><?= e(($c['paciente_nombre'] ?? '') . ' ' . ($c['paciente_apellido'] ?? '')) ?></strong></td>
                                                <td class="td-consultas"><?= e(($c['medico_nombre'] ?? '') . ' ' . ($c['medico_apellido'] ?? '')) ?></td>
                                                <td class="td-consultas"><?= e($c['motivo_de_visita']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($paginaActual === 'configuracion' && ($tieneGestionarRolesPermisos || $tieneGestionarCondiciones)): ?>
            <div id="seccion-configuracion" class="configuracion-container seccion-configuracion-box">
                <div class="nested-tabs-menu menu-subtabs">
                    <?php if ($tieneGestionarRolesPermisos): ?>
                    <a href="#" id="sub-tab-general" class="sub-tab-link subtab-link-comun subtab-link-general">General</a>
                    <a href="#" id="sub-tab-roles" class="sub-tab-link subtab-link-comun subtab-link-roles">Roles y Permisos</a>
                    <?php endif; ?>
                    <?php if ($tieneGestionarCondiciones): ?>
                    <a href="#" id="sub-tab-condiciones" class="sub-tab-link subtab-link-comun subtab-link-condiciones" style="<?= !$tieneGestionarRolesPermisos ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">Condiciones</a>
                    <?php endif; ?>
                </div>

                <?php if ($tieneGestionarRolesPermisos): ?>
                    <div id="sub-content-general" class="sub-tab-content subcontent-general-box configuracion-bloque-formulario">
                        <h3 class="titulo-configuracion-interna">Configuración General</h3>
                        <form action="index.php" method="POST" class="form-configuracion-flex">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="guardar_configuracion">
                            
                            <label class="label-rol-defecto">
                                <span class="texto-label-negrita">Rol por defecto en registro</span>
                                <select name="rol_defecto" required class="action-card__select select-rol-defecto">
                                    <?php 
                                        $currentDefRol = $userModel->obtenerRolDefecto();
                                        foreach ($roles as $role): 
                                            $sel = ($role['id_rol'] == $currentDefRol) ? 'selected' : '';
                                    ?>
                                        <option value="<?= e($role['id_rol']) ?>" <?= $sel ?>><?= e($role['nombre_rol']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            
                            <div class="contenedor-btn-izq">
                                <button type="submit" class="action-card__button btn-guardar-config">Guardar Configuración</button>
                            </div>
                        </form>
                    </div>

                <div id="sub-content-roles" class="sub-tab-content subcontent-roles-box">
                    <div class="contenedor-form-nuevo-rol">
                        <h3 class="titulo-configuracion-interna">Crear Nuevo Rol</h3>
                        <form action="index.php" method="POST" class="form-configuracion-flex">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="registrar_rol">
                            
                            <div class="fila-inputs-rol">
                                <label class="col-nombre-rol">
                                    <span class="texto-label-negrita">Nombre del Rol</span>
                                    <input type="text" name="nombre_rol" required class="action-card__input input-ancho-total" placeholder="Ej. Enfermero">
                                </label>
                                
                                <label class="col-descripcion-rol">
                                    <span class="texto-label-negrita">Descripción</span>
                                    <input type="text" name="descripcion_rol" class="action-card__input input-ancho-total" placeholder="Ej. Personal de soporte médico">
                                </label>
                            </div>
                            
                            <div class="contenedor-btn-der">
                                <button type="submit" class="action-card__button btn-crear-rol">Crear Rol</button>
                            </div>
                        </form>
                    </div>

                    <hr class="separador-configuracion">

                    <div class="contenedor-roles-registrados">
                        <h3 class="titulo-configuracion-interna">Roles Registrados</h3>
                        <table class="tabla-consultas">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="tabla-roles-cabecera-izq">ID</th>
                                    <th class="tabla-roles-cabecera-izq">Nombre del Rol</th>
                                    <th class="tabla-roles-cabecera-izq">Descripción</th>
                                    <th class="tabla-roles-cabecera-centro">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $currentDefRol = $userModel->obtenerRolDefecto();
                                    foreach ($roles as $role): 
                                ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-roles-comun"><?= e($role['id_rol']) ?></td>
                                        <td class="td-roles-comun"><strong><?= e($role['nombre_rol']) ?></strong></td>
                                        <td class="td-roles-comun"><?= e($role['descripcion_rol']) ?></td>
                                        <td class="td-roles-acciones">
                                            <button type="button" class="action-card__button editar-rol btn-editar-rol-lista" data-id="<?= e($role['id_rol']) ?>" data-nombre="<?= e($role['nombre_rol']) ?>" data-descripcion="<?= e($role['descripcion_rol']) ?>">Editar</button>
                                            <form action="index.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este rol? Se reasignarán los usuarios asociados al rol por defecto.');" class="form-eliminar-inline">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <input type="hidden" name="form" value="eliminar_rol">
                                                <input type="hidden" name="id_rol" value="<?= e($role['id_rol']) ?>">
                                                <button type="submit" class="action-card__button action-card__button--red btn-eliminar-rol-lista">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="separador-configuracion">

                    <h3 class="titulo-configuracion-interna">Matriz de Asignación de Permisos</h3>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="form" value="guardar_roles_permisos">
                        <table class="tabla-consultas">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="th-matriz-permiso-desc">Permiso / Descripción</th>
                                    <?php foreach ($roles as $role): ?>
                                        <th class="th-matriz-rol"><?= e($role['nombre_role'] ?? $role['nombre_rol']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permisos as $perm): ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-matriz-desc">
                                            <div class="texto-configuracion-sistema"><?= e($perm['nombre_permiso']) ?></div>
                                            <div class="texto-matriz-permiso-sub"><?= e($perm['descripcion_permiso']) ?></div>
                                        </td>
                                        <?php foreach ($roles as $role): ?>
                                            <?php 
                                                $checked = isset($rolePermMap[$role['id_rol']][$perm['id_permiso']]) ? 'checked' : '';
                                            ?>
                                            <td class="td-matriz-checkbox">
                                                <input type="checkbox" name="permisos[<?= $role['id_rol'] ?>][]" value="<?= $perm['id_permiso'] ?>" <?= $checked ?>>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="contenedor-btn-der class-margin-top-20"> <button type="submit" class="action-card__button">Guardar Matriz de Permisos</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <?php if ($tieneGestionarCondiciones): ?>
                <div id="sub-content-condiciones" class="sub-tab-content subcontent-condiciones-box" style="display: <?= $tieneGestionarRolesPermisos ? 'none' : 'block' ?>;">
                    <div class="contenedor-roles-registrados">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <h3 class="titulo-configuracion-interna" style="margin:0;">Condiciones Registradas</h3>
                            <button type="button" class="action-card__button" name="openModal" data-modal="modalRegistrarCondicion">Registrar Condición</button>
                        </div>
                        <div style="margin-bottom:15px;">
                            <input type="text" id="inputBuscarCondicion" placeholder="Buscar condición por nombre o descripción..." class="action-card__input input-ancho-total" autocomplete="off" style="width:100%; box-sizing:border-box;">
                        </div>
                        <table class="tabla-consultas" id="tablaCondiciones">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="tabla-roles-cabecera-izq">ID</th>
                                    <th class="tabla-roles-cabecera-izq">Nombre de la Condición</th>
                                    <th class="tabla-roles-cabecera-izq">Descripción</th>
                                    <th class="tabla-roles-cabecera-centro">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaCondiciones">
                                <?php if (empty($condicionesRegistradas)): ?>
                                    <tr><td colspan="4" class="td-tabla-vacia">No hay condiciones registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($condicionesRegistradas as $cond): ?>
                                        <tr class="tr-body-consultas">
                                            <td class="td-roles-comun"><?= e($cond['id']) ?></td>
                                            <td class="td-roles-comun"><strong><?= e($cond['nombre_condicion']) ?></strong></td>
                                            <td class="td-roles-comun"><?= e($cond['descripcion_condicion']) ?></td>
                                            <td class="td-roles-acciones">
                                                <button type="button" class="action-card__button editar-condicion btn-editar-rol-lista" data-id="<?= e($cond['id']) ?>" data-nombre="<?= e($cond['nombre_condicion']) ?>" data-descripcion="<?= e($cond['descripcion_condicion']) ?>">Editar</button>
                                                <form action="index.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta condición? Se eliminará de todos los usuarios asociados.');" class="form-eliminar-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="form" value="eliminar_condicion">
                                                    <input type="hidden" name="id" value="<?= e($cond['id']) ?>">
                                                    <button type="submit" class="action-card__button action-card__button--red btn-eliminar-rol-lista">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
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
        <dialog id="modalRegistrarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarUsuario.php" ?>
        </dialog>
        <dialog id="modalActualizarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarUsuario.php" ?>
        </dialog>
        <dialog id="modalDetallesUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalDetallesUsuario.php" ?>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneVerConsultas): ?>
        <dialog id="modalRegistrarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarConsulta.php" ?>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarConsulta.php" ?>
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalBuscarConsulta.php" ?>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneGenerarReportes): ?>
        <dialog id="modalReporteMorbilidad" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
            <?php include_once __DIR__."/modalReporteMorbilidad.php" ?>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneGestionarRolesPermisos): ?>
        <dialog id="modalEditarRol" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Editar Rol</h3>
                <form action="index.php" method="POST" class="form-configuracion-flex">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="actualizar_rol">
                    <input type="hidden" name="id_rol" id="edit_id_rol">
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Nombre del Rol</span>
                        <input type="text" name="nombre_rol" id="edit_nombre_rol" required class="action-card__input">
                    </label>
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Descripción</span>
                        <input type="text" name="descripcion_rol" id="edit_descripcion_rol" class="action-card__input">
                    </label>
                    
                    <div class="action-card__button-grid class-margin-top-10">
                        <button type="submit" class="action-card__button">Guardar Cambios</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalEditarRol" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
            <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>

            </svg>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneGestionarCondiciones): ?>
        <dialog id="modalRegistrarCondicion" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Registrar Condición</h3>
                <form action="index.php" method="POST" class="form-configuracion-flex">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="registrar_condicion">
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Nombre de la Condición</span>
                        <input type="text" name="nombre_condicion" required class="action-card__input">
                    </label>
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Descripción</span>
                        <input type="text" name="descripcion_condicion" class="action-card__input">
                    </label>
                    
                    <div class="action-card__button-grid class-margin-top-10">
                        <button type="submit" class="action-card__button">Registrar Condición</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarCondicion" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
            <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>

            </svg>
        </dialog>

        <dialog id="modalEditarCondicion" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Editar Condición</h3>
                <form action="index.php" method="POST" class="form-configuracion-flex">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="actualizar_condicion">
                    <input type="hidden" name="id" id="edit_id_condicion">
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Nombre de la Condición</span>
                        <input type="text" name="nombre_condicion" id="edit_nombre_condicion" required class="action-card__input">
                    </label>
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Descripción</span>
                        <input type="text" name="descripcion_condicion" id="edit_descripcion_condicion" class="action-card__input">
                    </label>
                    
                    <div class="action-card__button-grid class-margin-top-10">
                        <button type="submit" class="action-card__button">Guardar Cambios</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalEditarCondicion" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
            <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>

            </svg>
        </dialog>
        <?php endif; ?>
        <script>
            const ES_MEDICO_O_DIRECTOR = <?= $tieneModificarConsulta ? 'true' : 'false' ?>;
        </script>

        <dialog id="modalVerDetallesConsulta" class="modal-crud">
            <div class="action-card modal-detalles-ancho">
                <h3 class="action-card__title">Detalles de la Consulta Médica</h3>
                
                <div class="modal-detalles-contenedor-datos">
                    <div><strong>Fecha:</strong> <span id="det_fecha"></span></div>
                    <div><strong>Paciente:</strong> <span id="det_paciente"></span></div>
                    <div><strong>Médico Tratante:</strong> <span id="det_medico"></span></div>
                    <div><strong>Motivo de Visita:</strong> <span id="det_motivo"></span></div>
                    <div><strong>Observaciones:</strong> <span id="det_observaciones"></span></div>
                    <div><strong>Medicamento Suministrado:</strong> <span id="det_medicamento"></span></div>
                    <div><strong>Síntomas:</strong> <span id="det_sintomas"></span></div>
                    <div><strong>Diagnósticos (CIE-10):</strong> <div id="det_diagnosticos" class="modal-detalles-diagnosticos-box"></div></div>
                </div>
                
                <div class="contenedor-btn-der class-margin-top-20">
                    <button type="button" class="action-card__button btn-crear-rol" onclick="const m = document.getElementById('modalVerDetallesConsulta'); m.style.opacity = 0; setTimeout(() => m.close(), 500);">Cerrar</button>
                </div>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalVerDetallesConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
            <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>


            </svg>
        </dialog>
       
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