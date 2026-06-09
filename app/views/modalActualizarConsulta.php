<div class="action-card">
                <h3 class="action-card__title">Actualizar Consulta Médica</h3>
                <div class="action-card__form--grid">
                    <label class="action-card__label">Buscar Paciente
                        <input type="text" id="paciente-search-actualizar" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off">
                        <input type="hidden" id="cedula_paciente_actualizar">
                        <div id="pacientes-sugerencias-actualizar" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                    </label>
                </div>

                <!-- Contenedor para mostrar condiciones crónicas actuales del paciente al editar -->
                <div id="paciente-condiciones-info-actualizar" style="margin-top: 10px; display: none; padding: 10px; background-color: #f9f9f9; border: 1px solid #eee; border-radius: 4px;"></div>

                <div id="consultas-lista-actualizar" style="margin-top: 15px;"></div>

                <form id="formulario-edicion-consulta" class="action-card__form--registrar-usuarios" action="index.php" method="POST" style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="actualizar_consulta">
                    <input type="hidden" id="edit_id_consulta" name="id_consulta">

                    <h4 style="margin-bottom: 15px;">Editar Detalles de la Consulta</h4>

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
                            <div class="sintomas-input-group" style="display: flex; gap: 8px;">
                                <input type="text" id="edit-sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos">
                                <button type="button" id="btn-edit-add-sintoma" class="action-card__button" style="width: auto;">Añadir</button>
                            </div>
                            <ul id="edit-sintomas-lista" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                        </label>

                        <label class="action-card__label">Diagnósticos de la Visita (Agudos / Temporales - ICD-10)
                            <input type="text" id="edit-diagnostico-search" class="action-card__input" placeholder="Buscar patología..." autocomplete="off">
                            <div id="edit-diagnosticos-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                            <ul id="edit-diagnosticos-seleccionados" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                        </label>

                        <label class="action-card__label">Condiciones Crónicas / Permanentes del Paciente
                            <input type="text" id="edit-condicion-search" class="action-card__input" placeholder="Buscar y añadir condición crónica..." autocomplete="off">
                            <div id="edit-condiciones-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                            <ul id="edit-condiciones-seleccionadas" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                        </label>
                    </div>

                    <div class="action-card__button-grid" style="margin-top: 15px;">
                        <button type="submit" class="action-card__button">Guardar Cambios</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>