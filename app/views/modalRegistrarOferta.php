<div class="action-card">
        <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="UPTAEB">
        <h3 class="action-card__title">Registrar Oferta Académica</h3>
        
        <div id="alert-container-oferta" style="margin-bottom: 1rem; width: 100%;"></div>

        <form class="action-card__form" action="index.php" method="POST" id="formRegistrarOferta">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="form" value="registrar_oferta" required>
            
            <div class="action-card__form--grid" style="display: block; margin-bottom: 1.5rem;">
                
                <label for="id_nucleo_oferta" class="action-card__label" style="margin-bottom: 1.2rem; display: block;">Seleccione el Núcleo
                    <select class="action-card__input" id="id_nucleo_oferta" name="id_nucleo" required>
                        <option value="" disabled selected>Seleccione una sede...</option>
                        <?php if (!empty($nucleos)): ?>
                            <?php foreach ($nucleos as $n): ?>
                                <option value="<?= $n['id_nucleo'] ?>"><?= e($n['nombre_nucleo']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay núcleos disponibles</option>
                        <?php endif; ?>
                    </select>
                </label>

                <label for="id_pnf_oferta" class="action-card__label">Seleccione el PNF
                    <select class="action-card__input" id="id_pnf_oferta" name="id_pnf" required>
                        <option value="" disabled selected>Seleccione un PNF...</option>
                        <?php if (!empty($pnfs)): ?>
                            <?php foreach ($pnfs as $pnf): ?>
                                <option value="<?= $pnf['id_pnf'] ?>"><?= e($pnf['nombre_pnf']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay PNFs disponibles</option>
                        <?php endif; ?>
                    </select>
                </label>

            </div>
            
            <div class="action-card__button-grid">
                <button type="button" class="action-card__button action-card__button--red" onclick="document.getElementById('modalRegistrarOferta').close()">Cancelar</button>
                <button type="submit" class="action-card__button">Vincular Oferta</button>
            </div>
        </form>
    </div>

    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarOferta" onclick="document.getElementById('modalRegistrarOferta').close()" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.775 460.775" xml:space="preserve">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
    </svg>


