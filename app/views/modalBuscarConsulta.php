<div class="action-card">
                <h3 class="action-card__title">Historial de Consultas Médicas</h3>
                <div class="action-card__form--grid">
                    <label class="action-card__label">Buscar Paciente
                        <input type="text" id="paciente-search-buscar" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off">
                        <input type="hidden" id="cedula_paciente_buscar">
                        <div id="pacientes-sugerencias-buscar" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                    </label>
                </div>

                <!-- Contenedor para mostrar condiciones crónicas actuales del paciente en historial -->
                <div id="paciente-condiciones-info-buscar" style="margin-top: 10px; display: none; padding: 10px; background-color: #f9f9f9; border: 1px solid #eee; border-radius: 4px;"></div>

                <div id="consultas-lista-buscar" style="margin-top: 15px; max-height: 350px; overflow-y: auto;"></div>
            </div>