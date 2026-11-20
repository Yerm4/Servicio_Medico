<?php

$titulo = "Usuarios";
include_once __DIR__."/layout/header.php";
?>


<main class="perfil">    
    <?php include_once __DIR__."/layout/sidebar.php"; ?>
    
    <section class="section-1 section-1--perfil">
    <div class="buscador-caja">
    
    <div class="section-1__box transition" id="section-1-box">
        <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
    </div>
</div>

<table id="tablaRegistros">
    <thead>
    <tr>
        <th class="tabla-usuarios__title">Cédula</th>
        <th class="tabla-usuarios__title">Nombre</th>
        <th class="tabla-usuarios__title">Apellido</th>
        <th class="tabla-usuarios__title">Tipo</th>
        <th class="tabla-usuarios__title">Edad</th>
        <th class="tabla-usuarios__title">Teléfono</th>
        <th class="tabla-usuarios__title">Acciones</th>
    </tr>
    </thead>
    <tbody id="cuerpoTablaUsuarios">
        <tr><td colspan="7">Cargando usuarios...</td></tr>
    </tbody>
</table>
    </section>
    <script src="assets/script/gestionUsuario.js" defer></script>
    <!-- Modales -->
    <?php include_once __DIR__."/modals/modalRegistrarUsuario.php" ?>
    <?php include_once __DIR__."/modals/modalActualizarUsuario.php" ?>
    <?php include_once __DIR__."/modals/modalDetallesUsuario.php" ?>

</main>
<footer>
</footer>