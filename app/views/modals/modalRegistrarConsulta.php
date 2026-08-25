<dialog id="modalRegistrarConsulta" class="modal-crud">
    <div class="action-card">
    <h3 class="action-card__title">Registrar Consulta Médica</h3>
    <form class="action-card__form--registrar-usuarios" action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="form" value="registro_consulta">

        <div class="action-card__form--grid">    
            <label class="action-card__label">Paciente (Buscar por Cédula o Nombre)
                <input type="text" id="paciente-search" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off" required>
                <input type="hidden" id="cedula_paciente" name="cedula_paciente">
                <div id="pacientes-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
            </label>

            <label class="action-card__label">Condiciones Crónicas / Permanentes del Paciente
                <input type="text" id="condicion-search" class="action-card__input" placeholder="Buscar y añadir condición crónica (ej. Asma, Diabetes)..." autocomplete="off" disabled>
                <div id="condiciones-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                <ul id="condiciones-seleccionadas" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
            </label>

            <label for="motivo_de_visita" class="action-card__label">Motivo de la Visita
                <textarea class="action-card__input" id="motivo_de_visita" name="motivo_de_visita" required disabled></textarea>
            </label>

            <label for="observaciones" class="action-card__label">Observaciones
                <textarea class="action-card__input" id="observaciones" name="observaciones" disabled></textarea>
            </label>

            <label for="medicamento_suministrado" class="action-card__label">Medicamento Suministrado (Opcional)
                <input type="text" class="action-card__input" id="medicamento_suministrado" name="medicamento_suministrado" placeholder="Ej. Paracetamol 500mg" disabled>
            </label>

            <label class="action-card__label">Añadir Síntomas
                <div class="sintomas-input-group" style="display: flex; gap: 8px;">
                    <input type="text" id="sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos" disabled spellcheck="false">
                    <button type="button" id="btn-add-sintoma" class="action-card__button" style="width: auto;" disabled>Añadir</button>
                </div>
                <ul id="sintomas-lista" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
            </label>

            <label class="action-card__label">Diagnósticos de la Visita (Agudos / Temporales - ICD-10)
                <input type="text" id="diagnostico-search" class="action-card__input" placeholder="Escriba código o nombre de la patología..." autocomplete="off" disabled>
                <div id="diagnosticos-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                <ul id="diagnosticos-seleccionados" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
            </label>
        </div>

        <div class="action-card__button-grid" style="margin-top: 20px;">
            <button type="reset" class="action-card__button action-card__button--red">Limpiar Formulario</button>
            <button type="submit" id="btn-registrar-consulta-submit" class="action-card__button" disabled>Guardar en Sistema</button>
        </div>
    </form>
</div>
<svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
</svg>
</dialog>