<main class="perfil">
    <?php include __DIR__ . "/layouts/sidebar.php"; ?>

    <section class="section-1 section-1--perfil">
        <?php if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos): ?>
            <div class="contenedor-tabla-consultas">
                <h3 class="titulo-tabla-consultas">Mi Historial Médico</h3>
                <?php if (!empty($misConsultas)): ?>
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
                <?php else: ?>
                    <div class="contenedor-historial-vacio">
                        <p class="texto-historial-vacio">No hay consultas médicas asociadas a este usuario.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Dashboard de Administración / Médico -->
            <div class="dashboard-container">
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
                    <h3 class="titulo-tabla-consultas">Últimas Consultas Registradas</h3>
                    <!-- Tabla de consultas recientes en dashboard -->
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>