<dialog id="modalActualizarNucleo" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
    <div class="action-card">
        <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="Logo UPTAEB">
        <h3 class="action-card__title">Actualizar Datos</h3>
        <p class="action-card__p">Modificación del Núcleo</p>
        
        <form id="formActualizarNucleo" class="action-card__form--registrar-usuarios">
            <div class="action-card__form--grid">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <input type="hidden" name="form" value="actualizar_nucleo">
                
                <input type="hidden" name="id_nucleo" id="edit_id_nucleo">

                <label for="edit_nombre_nucleo" class="action-card__label">Nombre del Núcleo
                    <input 
                        type="text" 
                        class="action-card__input" 
                        id="edit_nombre_nucleo" 
                        name="nombre_nucleo" 
                        required 
                        minlength="4" 
                        maxlength="100" 
                        pattern="^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\(\)]+$" 
                        title="Solo se permiten letras y espacios. Mínimo 4 caracteres."
                        placeholder="Ej. Núcleo Barquisimeto"
                    >
                </label>
            </div> 
            
            <div class="action-card__button-grid" style="margin-top: 15px;">
                <button type="submit" class="action-card__button">Guardar Cambios</button>
            </div>
        </form>
    </div>
</dialog>