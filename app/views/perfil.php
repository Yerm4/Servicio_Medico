
<?php 
$titulo = "Login";
include __DIR__."/header.php";
?>
<main class="perfil">    
    <aside class="side-menu">
        <h1>Menu</h1>
        <hr>
        <a href="perfil" id="inicio" class="focus">Inicio</a>
        <a href="usuarios" id="usuario" class="">Usuarios</a>
        <a href="consultas" id="consulta" class="">Consultas</a>
        <a href="configuracion" id="configuracion" class="">Configuración</a>
    </aside>
    
    <section class="section-1 section-1--perfil">
        <div class="buscador-caja">
            <div class="section-1__box transition" id="section-1-box"></div>
        </div>

        <div class="dashboard-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 class="titulo-configuracion-interna" style="margin: 0;">Panel de Inicio</h3>
                <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#" style="background-color: #0284c7; width: fit-content; text-align: center;">Generar Reporte de Morbilidad</a>
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

        <?php if (!empty($_SESSION["registro_msg"])): ?>
            <div class="notification-banner notification-banner--<?= $_SESSION["registro_status"] ?>">
                <p><strong><?php echo e($_SESSION["registro_msg"]); unset($_SESSION["registro_msg"]); ?></strong></p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Modales -->
    <dialog id="modalRegistrarUsuario" class="modal-crud"><?php include_once __DIR__."/modals/modalRegistrarUsuario.php" ?></dialog>
    <dialog id="modalActualizarUsuario" class="modal-crud"><?php include_once __DIR__."/modals/modalActualizarUsuario.php" ?></dialog>
    <dialog id="modalDetallesUsuario" class="modal-crud"><?php include_once __DIR__."/modals/modalDetallesUsuario.php" ?></dialog>
    <dialog id="modalRegistrarConsulta" class="modal-crud"><?php include_once __DIR__."/modals/modalRegistrarConsulta.php" ?></dialog>
    <dialog id="modalActualizarConsulta" class="modal-crud"><?php include_once __DIR__."/modals/modalActualizarConsulta.php" ?></dialog>
    <dialog id="modalBuscarConsulta" class="modal-crud"><?php include_once __DIR__."/modals/modalBuscarConsulta.php" ?></dialog>
    <dialog id="modalReporteMorbilidad" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;"><?php include_once __DIR__."/modals/modalReporteMorbilidad.php" ?></dialog>
    
    <script>
        const ES_MEDICO_O_DIRECTOR = <?= isset($tieneModificarConsulta) && $tieneModificarConsulta ? 'true' : 'false' ?>;
    </script>
</main>

<footer>
    <script src="assets/script/append.js" defer></script>
    <script src="assets/script/eliminar.js" defer></script>
    <script src="assets/script/gestion.js" defer></script>
    <script src="assets/script/gestionpnfnucleo.js" defer></script>
    <script src="assets/script/gestionoferta.js" defer></script>
</footer>