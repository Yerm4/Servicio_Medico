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