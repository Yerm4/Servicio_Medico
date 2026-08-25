<dialog id="modalRegistrarCondicion" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Registrar Condición</h3>
                <form action="index.php" method="POST" class="form-configuracion-flex">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="registrar_condicion">
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Nombre de la Condición</span>
                        <input type="text" name="nombre_condicion" required class="action-card__input">
                    </label>
                    
                    <label class="action-card__label col-nombre-rol">
                        <span class="texto-label-negrita">Descripción</span>
                        <input type="text" name="descripcion_condicion" class="action-card__input">
                    </label>
                    
                    <div class="action-card__button-grid class-margin-top-10">
                        <button type="submit" class="action-card__button">Registrar Condición</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarCondicion" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
            <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>

            </svg>
        </dialog>