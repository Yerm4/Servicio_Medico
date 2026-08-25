<div class="buscador-caja">
    <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
        <input type="text" id="inputBuscarUsuario" placeholder="Buscar por cédula o nombre" class="action-card__input input-margin-bottom" autocomplete="off">
    <?php endif; ?>
    
    <?php if ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
        <input type="text" id="inputBuscarConsulta" placeholder="Buscar consulta por paciente, médico, cédula o motivo" class="action-card__input input-buscar-consulta" autocomplete="off">
    <?php endif; ?>
    
    <div class="section-1__box transition" id="section-1-box">
        <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
            <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
        <?php elseif ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
            <div class="box-iniciar-consulta" style="display: flex; gap: 10px;">
                <?php if ($tieneRealizarConsulta): ?>
                    <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal btn-iniciar-consulta" href="#">Iniciar consulta</a>
                <?php endif; ?>
                <?php if ($tieneGenerarReportes): ?>
                    <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#" style="background-color: #0284c7; width: fit-content; text-align: center;">Generar Reporte</a>
                <?php endif; ?>
            </div>
        <?php elseif ($paginaActual === 'configuracion' && ($tieneGestionarRolesPermisos || $tieneGestionarCondiciones)): ?>
            <span class="texto-configuracion-sistema">Configuración del Sistema</span>
        <?php elseif ($paginaActual === 'sesion'): ?>
            <a href="logout" class="link-cerrar-sesion">Cerrar sesión</a>
        <?php endif; ?>
    </div>
</div>