<?php
if ($_SESSION["cedula"]) {
    
}

else {
    header("Location: login");
}

$inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];

unset($_SESSION['inputs']);

?>

<main class="perfil">    
        <aside class="side-menu">
        <h1>Menu</h1>
            <hr>
            <a href="#" id="usuario">Usuarios</a>
            <a href="#" id="consulta">Consultas</a>
            <a href="#" id="sesion">Sesión</a>
        </aside>

        <section class="section-1 section-1--perfil transition">
            <div class="section-1__box" id="section-1">
                
                <div class="action-card">
                    <h2 class="action-card__title">Gestión de usuarios</h2>
                    <div class="action-card__button-grid">    
                        <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
                        <a name="openModal" data-modal="modalActualizarUsuario" class="action-card__button" href="">Actualizar usuario</a>
                        <a name="openModal" data-modal="modalBuscarUsuario" class="action-card__button" href="">Buscar usuario</a>
                        <a name="openModal" data-modal="modalEliminarUsuario" class="action-card__button" href="">Eliminar usuario</a>
                    </div>
                </div>
                <?php if (!empty($_SESSION["registro_status"]) && !empty($_SESSION["registro_msg"])): ?>
                    <?php $titulo = $_SESSION["registro_status"] === 'success' ? '¡Operación Exitosa!' : '¡Atención!'; ?>
                    <div class="notification-banner notification-banner--<?= $_SESSION["registro_status"] ?>">
                        <h2><?= htmlspecialchars($titulo) ?></h2>
                        <p><?= htmlspecialchars($_SESSION["registro_msg"]) ?></p>
                    </div>
                    <?php 
                        unset($_SESSION["registro_status"]);
                        unset($_SESSION["registro_msg"]);
                    ?>
                <?php endif; ?>
            </div>
        </section>
        <dialog id="modalRegistrarUsuario" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Registro de usuarios</h3>
                
                <form class="action-card__form--registrar-usuarios" action="index.php" method="POST">
                <div class="action-card__form--grid">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="registro_paciente">
                    
                        <label for="cedula" class="action-card__label">Cédula de Identidad
                            <input type="tel" inputmode="numeric" pattern="[0, 9]*" class="action-card__input" id="cedula" name="cedula" value="<?php echo isset($inputs['cedula']) ? $inputs['cedula'] : ''; ?>" required>
                        </label>

                        <label for="tipo" class="action-card__label">Tipo de Usuario
                            <select class="action-card__select" id="tipo" name="tipo">
                                <?php $t = isset($inputs['tipo']) ? $inputs['tipo'] : ''; ?>
                                <option value="" <?php echo ($t === '') ? 'selected' : ''; ?> disabled>Seleccione...</option>
                                <option value="0" <?php echo ($t === '0') ? 'selected' : ''; ?>>Estudiante / Paciente</option>
                                <option value="1" <?php echo ($t === '1') ? 'selected' : ''; ?>>Enfermero</option>
                                <option value="2" <?php echo ($t === '2') ? 'selected' : ''; ?>>Médico</option>
                                <option value="3" <?php echo ($t === '3') ? 'selected' : ''; ?>>Director / Autoridad</option>
                            </select>
                        </label>
                    <label for="nombre" class="action-card__label">Nombres
                        <input type="text" class="action-card__input" id="nombre" name="nombre" value="<?php echo isset($inputs['nombre']) ? $inputs['nombre'] : ''; ?>" required>
                    </label>
                    <label for="apellido" class="action-card__label">Apellidos
                        <input type="text" class="action-card__input" id="apellido" name="apellido" value="<?php echo isset($inputs['apellido']) ? $inputs['apellido'] : ''; ?>" required>
                    </label>
                    <label for="pnf" class="action-card__label">PNF (Área académica)
                        <select class="action-card__select" id="pnf" name="pnf" required>
                            <?php $p = isset($inputs['pnf']) ? $inputs['pnf'] : ''; ?>
                            <option value="" <?php echo ($p === '') ? 'selected' : ''; ?> disabled>Seleccione PNF...</option>
                            <option value="1" <?php echo ($p === '1') ? 'selected' : ''; ?>>Informática</option>
                            <option value="2" <?php echo ($p === '2') ? 'selected' : ''; ?>>Administración</option>
                            <option value="3" <?php echo ($p === '3') ? 'selected' : ''; ?>>Higiene y Seguridad</option>
                        </select>
                    </label>
                    <label for="fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                        <input type="date" class="action-card__input" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($inputs['fecha_nacimiento']) ? $inputs['fecha_nacimiento'] : ''; ?>" required>
                    </label>
                    <label for="tlfprincipal" class="action-card__label">Teléfono Principal
                        <input type="text" class="action-card__input" id="tlfprincipal" name="tlfprincipal" value="<?php echo isset($inputs['tlfprincipal']) ? $inputs['tlfprincipal'] : ''; ?>" required>
                    </label>
                    <label for="tlfemergencia" class="action-card__label">Contacto de Emergencia
                        <input type="text" class="action-card__input" id="tlfemergencia" name="tlfemergencia" value="<?php echo isset($inputs['tlfemergencia']) ? $inputs['tlfemergencia'] : ''; ?>" required>
                    </label>

                </div>
                    
                    <label class="action-card__label">Sexo
                        <?php $s = isset($inputs['sexo']) ? $inputs['sexo'] : ''; ?>
                        
                        <label >Masculino
                            <input type="radio" name="sexo" id="sexo_m" value="1" <?php echo ($s === '1') ? 'checked' : ''; ?> required>
                        </label>
                    
                        <label >Femenino
                            <input type="radio" name="sexo" id="sexo_f" value="2" <?php echo ($s === '2') ? 'checked' : ''; ?>>
                        </label>
                    </label>
                    <div class="action-card__button-grid">
                    <button type="reset" class="action-card__button action-card__button--red">Limpiar Formulario</button>
                    <button type="submit" class="action-card__button">Guardar en Sistema</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>

        <dialog id="modalBuscarUsuario" class="modal-crud">
            <p>aaaaaaaaaaaaaaaabuscar</p>
        </dialog>
        <dialog id="modalActualizarUsuario" class="modal-crud">
            <p>aaaaaaaaaaaaactualizar</p>
        </dialog>
        <dialog id="modalEliminarUsuario" class="modal-crud">
            <p>eliminar</p>
        </dialog>
        <dialog id="modalRegistrarConsulta" class="modal-crud">
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
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-crud">
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
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-crud">
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
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalBuscarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        
    </main>
    <footer>
        <script src="assets/script/append.js" defer></script>
    </footer>
</body>
</html>