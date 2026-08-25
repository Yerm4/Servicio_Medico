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
    <?php if ($tieneGestionarOferta): ?>
        <a href="sedes-carreras" id="sedes-carreras" class="<?= $paginaActual === 'sedes-carreras' ? 'focus' : '' ?>">Nucleos y PNFS</a>
        <a href="oferta" id="oferta" class="<?= $paginaActual === 'oferta' ? 'focus' : '' ?>">Ofertas Academicas</a>
    <?php endif; ?>
</aside>