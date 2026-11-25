<?php

if (!isset($_SESSION["cedula"])) {
    header("Location: login");
    exit;
}

$titulo = "Consultas";
include_once __DIR__."/layout/header.php";
?>

<main class="perfil">    
    <?php include_once __DIR__."/layout/sidebar.php"; ?>

    <section class="section-1 section-1--perfil">
        <!-- Buscador y botones de acción -->
        <div class="buscador-caja">
            
            <div class="section-1__box transition" id="section-1-box">
                <div class="box-iniciar-consulta">
                    <?php if (!empty($permisosUsuario['realizar_consulta'])): ?>
                        <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal btn-iniciar-consulta" href="#">Iniciar consulta</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($permisosUsuario['generar_reportes'])): ?>
                        <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#">Generar Reporte</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

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
                    <tr><td colspan="7">Cargando consultas...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modales vinculados a la vista -->
    <?php include_once __DIR__."/modals/modalRegistrarConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalActualizarConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalVerDetallesConsulta.php"; ?>
    <?php include_once __DIR__."/modals/modalBuscarConsulta.php"; ?>
    
    <?php if (!empty($permisosUsuario['generar_reportes'])): ?>
        <?php include_once __DIR__."/modals/modalReporteMorbilidad.php"; ?>
    <?php endif; ?>

    <script>
        const ES_MEDICO_O_DIRECTOR = <?= !empty($permisosUsuario['modificar_consulta']) ? 'true' : 'false' ?>;
    </script>
</main>

<footer>
    <script src="assets/script/gestion.js" defer></script>
</footer>