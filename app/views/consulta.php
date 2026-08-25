<?php if ($tieneVerConsultas): ?>
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
            <?php if (!empty($consultasRecientes)): foreach ($consultasRecientes as $c): ?>
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
                        <?php if (!empty($c['diagnosticos'])): foreach ($c['diagnosticos'] as $diag): ?>
                            <div class="diagnostico-item-tabla">
                                <strong class="diagnostico-codigo"><?= e($diag['codigo_icd_diagnostico']) ?></strong> - <?= e($diag['patologia'] ?? 'Sin detalle') ?>
                            </div>
                        <?php endforeach; else: ?>
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
            <?php endforeach; else: ?>
                <tr><td colspan="7" class="td-tabla-vacia">No hay ninguna consulta asociada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="contenedor-btn-cargar">
        <button id="btnCargarMasConsultas" class="action-card__button btn-cargar-mas">Cargar más</button>
    </div>
</div>
<?php endif; ?>