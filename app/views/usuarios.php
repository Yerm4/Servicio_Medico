<?php if ($tieneGestionarUsuarios && $usuariosEncontrados): ?>
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
                <td class="tabla-usuarios__desc"><?= e($registro["cedula"]) ?></td>
                <td class="tabla-usuarios__desc"><div class="tabla__elipsis"><?= e($registro["nombre"]) ?></div></td>
                <td class="tabla-usuarios__desc"><div class="tabla__elipsis"><?= e($registro["apellido"]) ?></div></td>
                <td class="tabla-usuarios__desc"><?= e($registro["nombre_tipo"] ?? ($registro["tipo"] === 0 ? "Estudiante" : "Docente")) ?></td>
                <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"]) ?></td>
                <td class="tabla-usuarios__desc"><?= e($registro["tlfprincipal"]) ?></td>
                <td class="tabla-usuarios__desc">
                    <button class="consultar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Ver</button>
                </td>
                <td class="tabla-usuarios__desc">
                    <button class="editar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Editar</button>
                </td>
                <td class="tabla-usuarios__desc">
                    <button class="eliminar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>