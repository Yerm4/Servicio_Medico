<?php
$tGestionarUsuarios = !empty($permisosUsuario["gestionar_usuarios"]);
$tVerConsultas = !empty($permisosUsuario["ver_consultas"]);
$tConfiguracion = !empty($permisosUsuario["gestionar_roles_permisos"]) || !empty($permisosUsuario["gestionar_condiciones"]);
?>
<aside class="side-menu">
    <h1>Menu</h1>
    <hr>
    <a href="./perfil" id="inicio" class="<?= $paginaActual === 'perfil' ? 'focus' : '' ?>">Inicio</a>
    <?php if ($tGestionarUsuarios): ?>
    <a href="./usuarios" id="usuario" class="<?= $paginaActual === 'usuarios' ? 'focus' : '' ?>">Usuarios</a>
    <?php endif; ?>
    <?php if ($tVerConsultas): ?>
    <a href="./consultas" id="consulta" class="<?= $paginaActual === 'consultas' ? 'focus' : '' ?>">Consultas</a>
    <?php endif; ?>
    <?php if ($tConfiguracion): ?>
    <a href="./configuracion" id="configuracion" class="<?= $paginaActual === 'configuracion' ? 'focus' : '' ?>">Configuración</a>
    <?php endif; ?>
</aside>