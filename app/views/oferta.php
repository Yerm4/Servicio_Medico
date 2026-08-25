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