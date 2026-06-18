<div class="action-card">
    <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
    <h3 class="action-card__title">Registrate!</h3>
    <p class="action-card__p">Control de Acceso</p>
    
    <form class="action-card__form--registrar-usuarios" action="index.php" method="POST">
    <div class="action-card__form--grid">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="form" value="registro_paciente">
        
            <label for="cedula" class="action-card__label">Cédula de Identidad
                <input type="tel" inputmode="numeric" class="action-card__input" id="cedula" name="cedula" value="<?php echo isset($inputs['cedula']) ? $inputs['cedula'] : ''; ?>" required>
            </label>

            <label for="tipo" class="action-card__label">Tipo de Usuario
                <select class="action-card__select" id="tipo" name="tipo" required>
                    <?php $t = isset($inputs['tipo']) ? $inputs['tipo'] : ''; ?>
                    <option value="" <?php echo ($t === '') ? 'selected' : ''; ?> disabled>Seleccione...</option>
                    <option value="1" <?php echo ($t === '1') ? 'selected' : ''; ?>>Estudiante</option>
                    <option value="2" <?php echo ($t === '2') ? 'selected' : ''; ?>>Docente</option>
                    <option value="3" <?php echo ($t === '3') ? 'selected' : ''; ?>>Administrativo</option>
                    <option value="4" <?php echo ($t === '4') ? 'selected' : ''; ?>>Obrero</option>
                    <option value="5" <?php echo ($t === '5') ? 'selected' : ''; ?>>Comunidad</option>
                    <option value="6" <?php echo ($t === '6') ? 'selected' : ''; ?>>Personal Médico</option>
                </select>
            </label>
        <label for="nombre" class="action-card__label">Nombres
            <input type="text" class="action-card__input" id="nombre" name="nombre" value="<?php echo isset($inputs['nombre']) ? $inputs['nombre'] : ''; ?>" required>
        </label>
        <label for="apellido" class="action-card__label">Apellidos
            <input type="text" class="action-card__input" id="apellido" name="apellido" value="<?php echo isset($inputs['apellido']) ? $inputs['apellido'] : ''; ?>" required>
        </label>
        
        <label for="fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
            <input type="date" class="action-card__input" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($inputs['fecha_nacimiento']) ? $inputs['fecha_nacimiento'] : ''; ?>" required>
        </label>
        <label for="tlfprincipal" class="action-card__label">Teléfono Principal
            <input type="text" class="action-card__input" id="tlfprincipal" name="tlfprincipal" value="<?php echo isset($inputs['tlfprincipal']) ? $inputs['tlfprincipal'] : ''; ?>" required>
        </label>
        <label for="tlfemergencia" class="action-card__label">Teléfono de Emergencia
            <input type="text" class="action-card__input" id="tlfemergencia" name="tlfemergencia" value="<?php echo isset($inputs['tlfemergencia']) ? $inputs['tlfemergencia'] : ''; ?>" required>
        </label>
        <label for="nombre_contacto_emergencia" class="action-card__label">Nombre del Contacto de Emergencia
            <input type="text" class="action-card__input" id="nombre_contacto_emergencia" name="nombre_contacto_emergencia" value="<?php echo isset($inputs['nombre_contacto_emergencia']) ? $inputs['nombre_contacto_emergencia'] : ''; ?>" required>
        </label>
        
        <label for="direccion" class="action-card__label">Dirección
            <input type="text" class="action-card__input" id="direccion" name="direccion" value="<?php echo isset($inputs['direccion']) ? $inputs['direccion'] : ''; ?>" required>
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