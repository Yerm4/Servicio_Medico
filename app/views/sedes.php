<div id="sedes-carreras" class="contenedor-sedes-carreras" style="margin-top: 1.5rem; width: 100%; display: flex; flex-direction: column;">
    <div class="gestion-grid-superior" style="display: flex; flex-direction: column; width: 100%; gap: 2.5rem; margin-bottom: 2rem;">
        
        <!-- Tabla de Núcleos -->
        <div class="contenedor-tabla-consultas" style="width: 100%;">
            <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 class="titulo-tabla-consultas" style="margin: 0;">Núcleos</h3>
                <a name="openModal" data-modal="modalRegistrarNucleo" class="action-card__button" href="#">Registrar Nucleo</a>
            </div>
            <div id="alert-container-nucleo" style="margin-bottom: 1rem;"></div>
            <table id="tablaNucleos" class="tabla-consultas" style="width: 100%;">
                <thead>
                    <tr class="tr-head-consultas">
                        <th class="th-consultas">ID</th>
                        <th class="th-consultas">Nombre del Núcleo</th>
                        <th class="th-consultas">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaNucleos">
                    <?php if (!empty($nucleos)): $i = 1; foreach ($nucleos as $n): ?>
                        <tr class="tr-body-consultas">
                            <td class="td-consultas-nowrap"><?= $i++ ?></td>
                            <td class="td-consultas"><strong><?= e($n['nombre_nucleo']) ?></strong></td>
                            <td class="td-acciones-btn">
                                <div style="display: flex; gap: 10px;">
                                    <button class="action-card__button editar-nucleo" name="openModal" data-modal="modalActualizarNucleo" data-id="<?= e($n['id_nucleo']) ?>" data-nombre="<?= e($n['nombre_nucleo']) ?>">Actualizar</button>
                                    <form method="POST" action="index.php" class="form-eliminar-nucleo" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                        <input type="hidden" name="form" value="eliminar_nucleo">
                                        <input type="hidden" name="id_nucleo" value="<?= e($n['id_nucleo']) ?>">
                                        <button type="submit" class="action-card__button" style="background-color: #d9534f;">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3" class="td-tabla-vacia">No hay núcleos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabla de PNFs -->
        <div id="contenedor-tabla-dinamica">
            <div class="contenedor-tabla-consultas" style="width: 100%;">
                <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 class="titulo-tabla-consultas" style="margin: 0;">Programas de Formación Nacional (PNF)</h3>
                    <a name="openModal" data-modal="modalRegistrarPNF" class="action-card__button" href="#">Registrar PNF</a>
                </div>
                <div id="alert-container-pnf" style="margin-bottom: 1rem;"></div>
                <table id="tablaPnfs" class="tabla-consultas" style="width: 100%;">
                    <thead>
                        <tr class="tr-head-consultas">
                            <th class="th-consultas">ID</th>
                            <th class="th-consultas">Nombre del PNF</th>
                            <th class="th-consultas">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-pnf-body">
                        <?php if (!empty($pnfs)): $i = 1; foreach ($pnfs as $p): ?>
                            <tr class="tr-body-consultas">
                                <td class="td-consultas-nowrap"><?= $i++ ?></td>
                                <td class="td-consultas"><strong><?= e($p['nombre_pnf']) ?></strong></td>
                                <td class="td-acciones-btn">
                                    <div style="display: flex; gap: 10px;">
                                        <button class="action-card__button editar-pnf" name="openModal" data-modal="modalActualizarPNF" data-id="<?= e($p['id_pnf']) ?>" data-nombre="<?= e($p['nombre_pnf']) ?>">Actualizar</button>
                                        <form method="POST" action="index.php" class="form-eliminar-pnf" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                            <input type="hidden" name="form" value="eliminar_pnf">
                                            <input type="hidden" name="id_pnf" value="<?= e($p['id_pnf']) ?>">
                                            <button type="submit" class="action-card__button" style="background-color: #d9534f;">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="3" class="td-tabla-vacia">No hay PNFs registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>