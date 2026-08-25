<?php 
if (isset($_SESSION["cedula"])) {
    header("Location: perfil");
    exit();
}
$titulo = "login";
include __DIR__."/header.php";
?>

<main class="login">
        <section class="section-1">
            <div class="section-1__box">
            <div class="action-card">
                <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
                <h3 class="action-card__title">Servicio de Salud universitaria UPTAEB</h3>
                <p class="action-card__p">Control de Acceso</p>
                
                <form class="action-card__form" action="" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="login">
                    <label class="action-card__label"> Cédula de identidad
                        <input class="action-card__input" minlength="7" placeholder="Ej. 1532423" type="text" name="cedula" id="inputCedula">
                    </label>
                    <label class="action-card__label"> Contraseña
                        <input class="action-card__input" placeholder="*********" type="password" name="password">
                    </label>
                    <br>
                    <button class="action-card__button" type="submit">Ingresar al sistema</button>
                </form>
                    <p class="action-card__disclaimer">@ 2026 PNF Informatica - Universidad Politecnica Territorial de Lara Andres Eloy Blanco</p>
                    <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="#">No tienes una cuenta? Registrate!</a>
                    
                    <?php if (!empty($_SESSION["login_notif"])): ?>
                        <div>
                            <strong><?= $_SESSION["login_notif"]; unset($_SESSION["login_notif"])?></strong>
                        </div>
                        
                    <?php endif; ?>
                    <?php if (!empty($_SESSION["registro_msg"])): ?>
                        <div>
                            <strong><?= $_SESSION["registro_msg"]; unset($_SESSION["registro_msg"]) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <dialog id="modalRegistrarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modal/modalRegistrarUsuario.php" ?>
        </dialog>
    </main>
    <footer>

    </footer>
    <script src="assets/script/login.js"></script>
</body>
</html>