<?php 
    isset($_SESSION["cedula"]) ? header("Location: perfil") : "";
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
                        <input class="action-card__input" placeholder="Ej. 1532423" type="text" name="cedula" id="loginCardCedula">
                    </label>
                    <label class="action-card__label"> Contraseña
                        <input class="action-card__input" placeholder="*********" type="password" name="password">
                    </label>
                    
                    <button class="action-card__button" type="submit">Ingresar al sistema</button>
                </form>
                    <p class="action-card__disclaimer">@ 2026 PNF Informatica - Universidad Politecnica Territorial de Lara Andres Eloy Blanco</p>
                    <strong><?= isset($_SESSION["login_notif"]) ? $_SESSION["login_notif"] : "" ?></strong>
                    <?php unset($_SESSION["login_notif"]) ?>
                </div>
            </div>
        </section>
    </main>
    <footer>

    </footer>
</body>
</html>