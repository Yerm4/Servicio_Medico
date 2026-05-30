
<main>
        <section class="section-1">
            <div class="section-1__box">
                <div class="login-card">
                    <h1 class="login-card__title">Registro!</h1>
                    <form class="login-card__form" action="" method="POST">
                        <label class="login-card__label"> Cedula
                            <input class="login-card__input" type="text" name="cedula" id="">
                        </label>
                        <label class="login-card__label"> Contraseña
                            <input class="login-card__input" type="password" name="password">
                        </label>
                        <input type="hidden" name="form" value="registro">
                        <button class="login-card__submit" type="submit">Registro</button>
                    </form>
                <?= isset($_SESSION["registro_notif"]) ? $_SESSION["registro_notif"] : "" ?>
                <?php unset($_SESSION["registro_notif"])?>
    
                </div>
                <div class="login-card">
                    <img class="login-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
                    <h3 class="login-card__title">Servicio de Salud universitaria UPTAEB</h3>
                    <p class="login-card__p">Control de Acceso<p/>
                    
                    <form class="login-card__form" action="" method="POST">
                        <label class="login-card__label"> Cédula de identidad
                            <input class="login-card__input" placeholder="Ej. 1532423" type="text" name="cedula" id="loginCardCedula">
                        </label>
                        <label class="login-card__label"> Contraseña
                            <input class="login-card__input" placeholder="*********" type="password" name="password">
                        </label>
                            <input type="hidden" name="form" value="login">
                        <button class="login-card__submit" type="submit">Ingresar al sistema</button>
                    </form>
                    <p class="login-card__disclaimer">@ 2026 PNF Informatica - Universidad Politecnica Territorial de Lara Andres Eloy Blanco</p>
                    <?= isset($_SESSION["login_notif"]) ? $_SESSION["login_notif"] : "" ?>
                    <?php unset($_SESSION["login_notif"]) ?>
                </div>
            </div>
        </section>
    </main>
    <footer>

    </footer>
</body>
</html>