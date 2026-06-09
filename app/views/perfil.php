<?php

use app\controller\Controller;

if ($_SESSION["cedula"]) {
    $controller = new Controller($pdo);
    $usuariosEncontrados = $controller->consultar();
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
            <?php if($usuariosEncontrados): ?>

            <table>
                <tr>
                    <th class="tabla-usuarios__title">Nombre</th>
                    <th class="tabla-usuarios__title">Apellido</th>
                    <th class="tabla-usuarios__title">Edad</th>
                    <th class="tabla-usuarios__title">Sexo</th>
                    <th class="tabla-usuarios__title">Novias</th>
                    <th class="tabla-usuarios__title">Ultima Consulta</th>
                </tr>
                <?php foreach ($usuariosEncontrados as $registro): ?>
                    <tr>
                    <td class="tabla-usuarios__desc"> <?=e($registro["nombre"])?></td>
                    <td class="tabla-usuarios__desc"><?=e($registro["apellido"])?></td>
                    <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"])?></td>
                    <td class="tabla-usuarios__desc"><?= $registro["sexo"] === 1 ? "Masculino" : "Femenino" ?></td>
                    <td class="tabla-usuarios__desc">0</td>
                    <td class="tabla-usuarios__desc">15 A.C</td>
                </tr>
                <?php endforeach?>
            </table>

            <?php endif?>
        </section>
        <dialog id="modalRegistrarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarUsuario.php" ?>
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
        <!-- Consultas Medicas -->
        <dialog id="modalRegistrarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarConsulta.php" ?>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarConsulta.php" ?>
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalBuscarConsulta.php" ?>
        </dialog>
        
    </main>
    <footer>
        <script src="assets/script/append.js" defer></script>
    </footer>
</body>
</html>