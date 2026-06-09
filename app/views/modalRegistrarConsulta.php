<div class="action-card">
                <h3 class="action-card__title">Registrar Consulta Médica</h3>
                <form class="action-card__form--registrar-usuarios" action="index.php" method="POST">
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
                                <input type="text" id="sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos" disabled>
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