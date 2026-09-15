<?php 
$titulo = "Perfil";
include __DIR__."/layout/header.php";
?>
<main class="perfil">    
    <?php include_once __DIR__."/layout/sidebar.php"; ?>
    
    <section class="section-1 section-1--perfil">
        <div class="buscador-caja">
            <div class="section-1__box transition" id="section-1-box"></div>
        </div>

        <?php if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos): ?>
            <?php if (!empty($misCondiciones)): ?>
                <div class="condiciones-medicas-box">
                    <h4 class="condiciones-medicas-titulo">Condiciones Médicas / Crónicas Registradas</h4>
                    <div class="condiciones-medicas-lista">
                        <?php foreach ($misCondiciones as $cond): ?>
                            <span class="badge-condicion">
                                <?= e($cond['nombre_condicion'] ?? $cond['condicion'] ?? '') ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

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
                                <th class="th-consultas">Diagnóstico (CIE-10)</th>
                                <th class="th-consultas">Tratamiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($misConsultas as $c): ?>
                                <tr class="tr-body-consultas">
                                    <td class="td-consultas-nowrap"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
                                    <td class="td-consultas"><?= e(($c['medico_nombre'] ?? '') . ' ' . ($c['medico_apellido'] ?? '')) ?></td>
                                    <td class="td-consultas">
                                        <?= e($c['motivo_de_visita']) ?>
                                        <?php if (!empty($c['observaciones'])): ?>
                                            <div class="td-paciente-sub"><strong>Obs:</strong> <?= e($c['observaciones']) ?></div>
                                        <?php endif; ?>
                                    </td>
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
                <div class="dashboard-cabecera">
                    <h3 class="titulo-configuracion-interna">Panel de Inicio</h3>
                    <div class="dashboard-acciones">
                        <?php if (!empty($tieneRealizarConsulta) || !empty($GLOBALS['tieneRealizarConsulta'])): ?>
                            <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal btn-iniciar-consulta" href="#"><p>Iniciar consulta</p></a>
                        <?php endif; ?>
                        <?php if (!empty($tieneGenerarReportes) || !empty($GLOBALS['tieneGenerarReportes'])): ?>
                            <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#">Generar Reporte de Morbilidad</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-stats-grid">
                    <div class="stat-card">
                        <div class="stat-card__number"><?= $stats['total_consultas'] ?? 0 ?></div>
                        <div class="stat-card__label">Consultas Realizadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__number"><?= $stats['total_usuarios'] ?? 0 ?></div>
                        <div class="stat-card__label">Usuarios Registrados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__number"><?= $stats['total_condiciones'] ?? 0 ?></div>
                        <div class="stat-card__label">Condiciones Médicas</div>
                    </div>
                </div>

                <div class="contenedor-tabla-consultas">
                    <h3 class="titulo-tabla-consultas titulo-tabla-consultas--left">Últimas Consultas Registradas</h3>
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

        <?php if (!empty($_SESSION["registro_msg"])): ?>
            <div class="notification-banner notification-banner--<?= $_SESSION["registro_status"] ?>">
                <p><strong><?php echo e($_SESSION["registro_msg"]); unset($_SESSION["registro_msg"]); ?></strong></p>
            </div>
        <?php endif; ?>
    </section>

    <?php include_once __DIR__."/modals/modalRegistrarUsuario.php"; ?>
    <?php include_once __DIR__."/modals/modalActualizarUsuario.php"; ?>
    <?php include_once __DIR__."/modals/modalDetallesUsuario.php"; ?>
    <?php include_once __DIR__."/modals/modalRegistrarConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalActualizarConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalVerDetallesConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalBuscarConsulta.php"; ?>
    <?php if (!empty($tieneGenerarReportes) || !empty($GLOBALS['tieneGenerarReportes'])): ?>
        <?php include_once __DIR__."/modals/modalReporteMorbilidad.php"; ?>
    <?php endif; ?>
    
    <script>
        const ES_MEDICO_O_DIRECTOR = <?= isset($tieneModificarConsulta) && $tieneModificarConsulta ? 'true' : 'false' ?>;
    </script>
</main>

<footer>
    <script src="assets/script/gestion.js" defer></script>
    <script src="assets/script/gestionPnfNucleo.js" defer></script>
    <script src="assets/script/gestionOferta.js" defer></script>
</footer>