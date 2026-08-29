<dialog id="modalVerDetallesConsulta" class="modal-crud">
    <div class="action-card modal-detalles-ancho">
        <h3 class="action-card__title">Detalles de la Consulta Médica</h3>
        
        <div class="modal-detalles-contenedor-datos">
            <div><strong>Fecha:</strong> <span id="det_fecha"></span></div>
            <div><strong>Paciente:</strong> <span id="det_paciente"></span></div>
            <div><strong>Médico Tratante:</strong> <span id="det_medico"></span></div>
            <div><strong>Motivo de Visita:</strong> <span id="det_motivo"></span></div>
            <div><strong>Observaciones:</strong> <span id="det_observaciones"></span></div>
            <div><strong>Medicamento Suministrado:</strong> <span id="det_medicamento"></span></div>
            <div><strong>Síntomas:</strong> <span id="det_sintomas"></span></div>
            <div><strong>Diagnósticos (CIE-10):</strong> <div id="det_diagnosticos" class="modal-detalles-diagnosticos-box"></div></div>
        </div>
        
        <div class="contenedor-btn-der class-margin-top-20">
            <button type="button" class="action-card__button btn-crear-rol" onclick="const m = document.getElementById('modalVerDetallesConsulta'); m.style.opacity = 0; setTimeout(() => m.close(), 500);">Cerrar</button>
        </div>
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalVerDetallesConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>


    </svg>
</dialog>