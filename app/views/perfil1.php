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