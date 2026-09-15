<dialog id="modalDetallesUsuario" class="modal-crud">
    <div class="action-card modal-detalles-ancho">
        <h3 class="action-card__title">Detalles del Usuario</h3>
        
        <div class="modal-detalles-contenedor-datos">
            <div><strong>Cédula:</strong> <span id="det_usr_cedula"></span></div>
            <div><strong>Nombre:</strong> <span id="det_usr_nombre"></span></div>
            <div><strong>Apellido:</strong> <span id="det_usr_apellido"></span></div>
            <div><strong>Tipo:</strong> <span id="det_usr_tipo"></span></div>
            <div><strong>Fecha de Nacimiento:</strong> <span id="det_usr_fecha_nacimiento"></span></div>
            <div><strong>Edad:</strong> <span id="det_usr_edad"></span></div>
            <div><strong>Sexo:</strong> <span id="det_usr_sexo"></span></div>
            <div><strong>Teléfono Principal:</strong> <span id="det_usr_tlfprincipal"></span></div>
            <div><strong>Teléfono Emergencia:</strong> <span id="det_usr_tlfemergencia"></span></div>
            <div><strong>Contacto de Emergencia:</strong> <span id="det_usr_contacto"></span></div>
            <div><strong>Dirección:</strong> <span id="det_usr_direccion"></span></div>
            <div><strong>Núcleo:</strong> <span id="det_usr_nucleo"></span></div>
            <div><strong>PNF:</strong> <span id="det_usr_pnf"></span></div>
        </div>
        
        <div class="contenedor-btn-der class-margin-top-20">
            <button type="button" class="action-card__button btn-crear-rol" onclick="const m = document.getElementById('modalDetallesUsuario'); m.style.opacity = 0; setTimeout(() => m.close(), 150);">Cerrar</button>
        </div>
    </div>
    
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalDetallesUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.775 460.775">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
    </svg>
</dialog>