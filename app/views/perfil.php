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
            <a href="#" id="usuario" class="focus">Usuarios</a>
            <a href="#" id="consulta">Consultas</a>
            <a href="#" id="sesion">Sesión</a>
        </aside>

        <section class="section-1 section-1--perfil">
            <div class="section-1__box transition" id="section-1-box">
                
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
            <?php include_once __DIR__."/modalRegistrarUsuario.php" ?>
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
            <?php include_once __DIR__."/modalRegistrarConsulta.php" ?>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarConsulta.php" ?>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalBuscarConsulta.php" ?>
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