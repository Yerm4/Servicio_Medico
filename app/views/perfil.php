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
            
            <div class="buscador-caja">
                <input type="text" id="inputBuscarUsuario" placeholder="Buscar por cédula o nombre" class="action-card__input" autocomplete="off">
                
                <div class="section-1__box transition" id="section-1-box">
                    <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
                </div>
            </div>
            

            <?php if($usuariosEncontrados): ?>

            <table id="tablaRegistros">
                <thead>
                <tr>
                    <th class="tabla-usuarios__title">Cedula</th>
                    <th class="tabla-usuarios__title">Nombre</th>
                    <th class="tabla-usuarios__title">Apellido</th>
                    <th class="tabla-usuarios__title">Tipo</th>
                    <th class="tabla-usuarios__title">Edad</th>
                    <th class="tabla-usuarios__title">Sexo</th>
                    <th class="tabla-usuarios__title">Telefono</th>
                    <th class="tabla-usuarios__title"></th>
                    <th class="tabla-usuarios__title"></th>
                </tr>
                </thead>
                <tbody id="cuerpoTablaUsuarios">
                <?php foreach ($usuariosEncontrados as $registro): ?>
                    <tr>
                    <td class="tabla-usuarios__desc"> <?=e($registro["cedula"])?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["nombre"])?></td>
                    <td class="tabla-usuarios__desc"><?=e($registro["apellido"])?></td>
                    <td class="tabla-usuarios__desc"><?= $registro["tipo"] === 0 ? "Estudiante" : "Docente" ?></td>
                    <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"])?></td>
                    <td class="tabla-usuarios__desc"><?= $registro["sexo"] === 1 ? "Masculino" : "Femenino" ?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["tlfprincipal"])?></td>
                    <td class="tabla-usuarios__desc"> <button class="editar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Actualizar</button></td>
                    <td class="tabla-usuarios__desc"> <button class="eliminar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Eliminar</button></td>
                </tr>
                <?php endforeach?>
                </tbody>
            </table>

            <?php endif?>
                
                <?php if (!empty($_SESSION["registro_status"]) && !empty($_SESSION["registro_msg"])): ?>
                        <?php $titulo = $_SESSION["registro_status"] === 'success' ? '¡Registro Exitoso!' : '¡Atención!'; ?>
                        <div>
                            <h2><?= $titulo; unset($_SESSION["registro_status"])?></h2>
                            <h2><?= $_SESSION["registro_msg"]; unset($_SESSION["registro_msg"]) ?></h2>
                        </div>
                        
                        <?php endif; ?>
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
        </dialog>
        <dialog id="modalActualizarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarUsuario.php" ?>
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
        <script src="assets/script/eliminar.js" defer></script>
        <script src="assets/script/consultarTabla.js" defer></script>
    </footer>
</body>
</html>