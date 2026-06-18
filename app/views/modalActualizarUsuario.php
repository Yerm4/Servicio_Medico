<div class="action-card">
    <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
    <h3 class="action-card__title">Actualizar Datos</h3>
    <p class="action-card__p">Modificación de Usuario</p>
    
    <form id="formActualizarUsuario" class="action-card__form--registrar-usuarios">
        <div class="action-card__form--grid">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="form" value="actualizar_usuario">
            
            <label for="edit_cedula" class="action-card__label">Cédula de Identidad
                <input type="tel" class="action-card__input" id="edit_cedula" name="cedula" readonly required>
            </label>

            <label for="edit_nombre" class="action-card__label">Nombre
                <input type="text" class="action-card__input" id="edit_nombre" name="nombre" required>
            </label>

            <label for="edit_apellido" class="action-card__label">Apellido
                <input type="text" class="action-card__input" id="edit_apellido" name="apellido" required>
            </label>

            <label for="edit_tipo" class="action-card__label">Tipo de Usuario
                <select class="action-card__select" id="edit_tipo" name="tipo" required>
                    <option value="" disabled>Seleccione...</option>
                    <option value="0">Estudiante</option>
                    <option value="1">Docente</option>
                    <option value="2">Obrero</option>
                    <option value="3">Administrativo</option>
                    <option value="4">Comunidad</option>
                </select>
            </label>

            <label for="edit_fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                <input type="date" class="action-card__input" id="edit_fecha_nacimiento" name="fecha_nacimiento" required>
            </label>

            <label for="edit_tlfprincipal" class="action-card__label">Teléfono Principal
                <input type="tel" class="action-card__input" id="edit_tlfprincipal" name="tlfprincipal" required>
            </label>

            <label for="edit_nombre_contacto_emergencia" class="action-card__label">Contacto de Emergencia
                <input type="text" class="action-card__input" id="edit_nombre_contacto_emergencia" name="nombre_contacto_emergencia" required>
            </label>

            <label for="edit_tlfemergencia" class="action-card__label">Teléfono de Emergencia
                <input type="tel" class="action-card__input" id="edit_tlfemergencia" name="tlfemergencia" required>
            </label>
            
            <label for="edit_direccion" class="action-card__label">Dirección
                <input type="text" class="action-card__input" id="edit_direccion" name="direccion" required>
            </label>

            <label class="action-card__label">Sexo
                <label for="edit_sexo_m">Masculino
                    <input type="radio" name="sexo" id="edit_sexo_m" value="1" required>
                </label>
            
                <label for="edit_sexo_f">Femenino
                    <input type="radio" name="sexo" id="edit_sexo_f" value="2">
                </label>
            </label>
        </div>

        <div class="action-card__button-grid" style="margin-top: 15px;">
            <button type="submit" class="action-card__button">Guardar Cambios</button>
        </div>
    </form>
</div>
<svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
</svg>