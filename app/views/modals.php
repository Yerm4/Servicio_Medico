<!-- Modales de Ofertas y PNFs -->
<?php if ($tieneGestionarOferta): ?>
    <dialog id="modalRegistrarPNF" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarPNF.php" ?></dialog>
    <dialog id="modalActualizarPNF" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalActualizarPNF.php" ?></dialog>
    <dialog id="modalRegistrarNucleo" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarNucleo.php" ?></dialog>
    <dialog id="modalActualizarNucleo" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalActualizarNucleo.php" ?></dialog>
    <dialog id="modalRegistrarOferta" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarOferta.php" ?></dialog>
<?php endif; ?>

<!-- Modales de Usuarios -->
<?php if ($tieneGestionarUsuarios): ?>
    <dialog id="modalRegistrarUsuario" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarUsuario.php" ?></dialog>
    <dialog id="modalActualizarUsuario" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalActualizarUsuario.php" ?></dialog>
    <dialog id="modalDetallesUsuario" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalDetallesUsuario.php" ?></dialog>
<?php endif; ?>

<!-- Modales de Consultas -->
<?php if ($tieneVerConsultas): ?>
    <dialog id="modalRegistrarConsulta" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarConsulta.php" ?></dialog>
    <dialog id="modalActualizarConsulta" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalActualizarConsulta.php" ?></dialog>
    <dialog id="modalBuscarConsulta" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalBuscarConsulta.php" ?></dialog>
    <dialog id="modalVerDetallesConsulta" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalVerDetallesConsulta.php" ?></dialog>
<?php endif; ?>

<!-- Modales de Reportes -->
<?php if ($tieneGenerarReportes): ?>
    <dialog id="modalReporteMorbilidad" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalReporteMorbilidad.php" ?></dialog>
<?php endif; ?>

<!-- Modales de Roles y Condiciones Extraídos -->
<?php if ($tieneGestionarRolesPermisos): ?>
    <dialog id="modalEditarRol" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalEditarRol.php" ?></dialog>
<?php endif; ?>

<?php if ($tieneGestionarCondiciones): ?>
    <dialog id="modalRegistrarCondicion" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalRegistrarCondicion.php" ?></dialog>
    <dialog id="modalEditarCondicion" class="modal-crud"><?php include_once __DIR__ . "/../modals/modalEditarCondicion.php" ?></dialog>
<?php endif; ?>