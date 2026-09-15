<dialog id="modalActualizarConsulta" class="modal-crud">
    <div class="action-card">
        <h3 class="action-card__title">Actualizar Consulta Médica</h3>
        
        <div id="seccion-busqueda-paciente-actualizar">
            <div class="action-card__form--grid">
                <label class="action-card__label action-card__label--relative">Buscar Paciente
                    <input type="text" id="paciente-search-actualizar" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off">
                    <input type="hidden" id="cedula_paciente_actualizar">
                    <div id="pacientes-sugerencias-actualizar" class="sugerencias-box" style="display: none;"></div>
                </label>
            </div>
        </div>

        <div id="paciente-condiciones-info-actualizar" class="paciente-info-box" style="display: none;"></div>

        <div id="consultas-lista-actualizar" class="consultas-lista-scroll"></div>

        <form id="formulario-edicion-consulta" class="action-card__form--registrar-usuarios formulario-edicion-seccion" action="index.php" method="POST" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="form" value="actualizar_consulta">
            <input type="hidden" id="edit_id_consulta" name="id_consulta">

            <h4 class="subtitulo-modal">Editar Detalles de la Consulta</h4>

            <div class="action-card__form--grid">
                <label for="edit_motivo_de_visita" class="action-card__label">Motivo de la Visita
                    <textarea class="action-card__input" id="edit_motivo_de_visita" name="motivo_de_visita" required></textarea>
                </label>

                <label for="edit_observaciones" class="action-card__label">Observaciones
                    <textarea class="action-card__input" id="edit_observaciones" name="observaciones"></textarea>
                </label>

                <label for="edit_medicamento_suministrado" class="action-card__label">Medicamento Suministrado (Opcional)
                    <input type="text" class="action-card__input" id="edit_medicamento_suministrado" name="medicamento_suministrado" placeholder="Ej. Paracetamol 500mg">
                </label>

                <label class="action-card__label">Síntomas
                    <div class="sintomas-input-group">
                        <input type="text" id="edit-sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos" spellcheck="false">
                        <button type="button" id="btn-edit-add-sintoma" class="action-card__button">Añadir</button>
                    </div>
                    <ul id="edit-sintomas-lista" class="lista-items-seleccionados"></ul>
                </label>

                <label class="action-card__label action-card__label--relative">Diagnósticos de la Visita (Agudos / Temporales - ICD-10)
                    <input type="text" id="edit-diagnostico-search" class="action-card__input" placeholder="Buscar patología..." autocomplete="off">
                    <div id="edit-diagnosticos-sugerencias" class="sugerencias-box" style="display: none;"></div>
                    <ul id="edit-diagnosticos-seleccionados" class="lista-items-seleccionados"></ul>
                </label>

                <label class="action-card__label action-card__label--relative">Condiciones Crónicas / Permanentes del Paciente
                    <input type="text" id="edit-condicion-search" class="action-card__input" placeholder="Buscar y añadir condición crónica..." autocomplete="off">
                    <div id="edit-condiciones-sugerencias" class="sugerencias-box" style="display: none;"></div>
                    <ul id="edit-condiciones-seleccionadas" class="lista-items-seleccionados"></ul>
                </label>
            </div>

            <div class="action-card__button-grid class-margin-top-20">
                <button type="submit" class="action-card__button">Guardar Cambios</button>
            </div>
        </form>
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.775 460.775">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
    </svg>
</dialog>