<?php
if ($_SESSION["cedula"]) {
    
}

else {
    header("Location: index.php");
}

$inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];

unset($_SESSION['inputs']);

?>

<main class="perfil">
        <section class="section-1">
            <div class="section-1__box">
                <div class="action-card">
                    <h2 class="action-card__title">Gestión de usuarios</h2>
                    <div class="action-card__button-grid">    
                        <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button action-card__button--grid-principal" href="">Registrar paciente</a>
                        <a name="openModal" data-modal="modalActualizarUsuario" class="action-card__button" href="">Actualizar paciente</a>
                        <a name="openModal" data-modal="modalBuscarUsuario" class="action-card__button" href="">Buscar usuario</a>
                    </div>
                </div>
                <div class="action-card">
                    <h2 class="action-card__title">Gestión de consultas</h2>
                    <div class="action-card__button-grid">    
                        <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal" href="">Iniciar consulta</a>
                        <a name="openModal" data-modal="modalActualizarConsulta" class="action-card__button" href="">Actualiza consulta</a>
                        <a name="openModal" data-modal="modalBuscarConsulta" class="action-card__button" href="">Buscar consulta</a>
                    </div>
                </div>
                <div class="action-card">
                    <h2>Gestión de sesión</h2>
                    <a href="logout" style="color: blue">Cerrar sesión</a>
                </div>
            </div>
        </section>
        <dialog id="modalRegistrarUsuario" class="modal-usuario">
            <div class="action-card">
                <?php if (!empty($_SESSION["registro_status"]) && !empty($_SESSION["registro_msg"])): ?>
                <?php $titulo = $_SESSION["registro_status"] === 'success' ? '¡Registro Exitoso!' : '¡Atención!'; ?>
                <div>
                    <h2><?= $titulo; unset($_SESSION["registro_status"])?></h2>
                    <h2><?= $_SESSION["registro_msg"]; unset($_SESSION["registro_msg"]) ?></h2>
                </div>
                
                <?php endif; ?>
                <h3 class="action-card__title">Registro de usuarios</h3>
                
                <form class="action-card__form--registrar-usuarios" action="index.php" method="POST">
                <div class="action-card__form--grid">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="registro_paciente">
                    
                        <label for="cedula" class="action-card__label">Cédula de Identidad
                            <input type="number" class="action-card__input" id="cedula" name="cedula" value="<?php echo isset($inputs['cedula']) ? $inputs['cedula'] : ''; ?>" required>
                        </label>

                        <label for="tipo" class="action-card__label">Tipo de Usuario
                            <select class="action-card__select" id="tipo" name="tipo">
                                <?php $t = isset($inputs['tipo']) ? $inputs['tipo'] : ''; ?>
                                <option value="" <?php echo ($t === '') ? 'selected' : ''; ?> disabled>Seleccione...</option>
                                <option value="0" <?php echo ($t === '0') ? 'selected' : ''; ?>>Estudiante / Paciente</option>
                                <option value="1" <?php echo ($t === '1') ? 'selected' : ''; ?>>Enfermero</option>
                                <option value="2" <?php echo ($t === '2') ? 'selected' : ''; ?>>Médico</option>
                                <option value="3" <?php echo ($t === '3') ? 'selected' : ''; ?>>Director / Autoridad</option>
                            </select>
                        </label>
                    <label for="nombre" class="action-card__label">Nombres
                        <input type="text" class="action-card__input" id="nombre" name="nombre" value="<?php echo isset($inputs['nombre']) ? $inputs['nombre'] : ''; ?>" required>
                    </label>
                    <label for="apellido" class="action-card__label">Apellidos
                        <input type="text" class="action-card__input" id="apellido" name="apellido" value="<?php echo isset($inputs['apellido']) ? $inputs['apellido'] : ''; ?>" required>
                    </label>
                    <label for="pnf" class="action-card__label">PNF (Área académica)
                        <select class="action-card__select" id="pnf" name="pnf" required>
                            <?php $p = isset($inputs['pnf']) ? $inputs['pnf'] : ''; ?>
                            <option value="" <?php echo ($p === '') ? 'selected' : ''; ?> disabled>Seleccione PNF...</option>
                            <option value="1" <?php echo ($p === '1') ? 'selected' : ''; ?>>Informática</option>
                            <option value="2" <?php echo ($p === '2') ? 'selected' : ''; ?>>Administración</option>
                            <option value="3" <?php echo ($p === '3') ? 'selected' : ''; ?>>Higiene y Seguridad</option>
                        </select>
                    </label>
                    <label for="fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                        <input type="date" class="action-card__input" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($inputs['fecha_nacimiento']) ? $inputs['fecha_nacimiento'] : ''; ?>" required>
                    </label>
                    <label for="tlfprincipal" class="action-card__label">Teléfono Principal
                        <input type="text" class="action-card__input" id="tlfprincipal" name="tlfprincipal" value="<?php echo isset($inputs['tlfprincipal']) ? $inputs['tlfprincipal'] : ''; ?>" required>
                    </label>
                    <label for="tlfemergencia" class="action-card__label">Contacto de Emergencia
                        <input type="text" class="action-card__input" id="tlfemergencia" name="tlfemergencia" value="<?php echo isset($inputs['tlfemergencia']) ? $inputs['tlfemergencia'] : ''; ?>" required>
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
                    <button type="reset" class="action-card__button">Limpiar Formulario</button>
                    <button type="submit" class="action-card__button">Guardar en Sistema</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="modalBuscarUsuario" class="modal-usuario">
            <p>aaaaaaaaaaaaaaaabuscar</p>
        </dialog>
        <dialog id="modalActualizarUsuario" class="modal-usuario">
            <p>aaaaaaaaaaaaactualizar</p>
        </dialog>
        <dialog id="modalRegistrarConsulta" class="modal-usuario">
            <div class="action-card">
                <?php if (!empty($_SESSION["registro_status"]) && !empty($_SESSION["registro_msg"])): ?>
                <?php $titulo = $_SESSION["registro_status"] === 'success' ? '¡Registro Exitoso!' : '¡Atención!'; ?>
                <div>
                    <h2><?= $titulo; unset($_SESSION["registro_status"])?></h2>
                    <h2><?= $_SESSION["registro_msg"]; unset($_SESSION["registro_msg"]) ?></h2>
                </div>
                
                <?php endif; ?>






                
                <h3 class="action-card__title">Registro de usuarios</h3>
                
                <form class="action-card__form--registrar-usuarios" action="index.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="form" value="registro_paciente">

                <div class="action-card__form--grid">    
                        <label for="cedula" class="action-card__label">Cédula de Identidad
                            <input type="number" class="action-card__input" id="cedula" name="cedula" value="<?php echo isset($inputs['cedula']) ? $inputs['cedula'] : ''; ?>" required>
                        </label>

                        <label for="tipo" class="action-card__label">Tipo de Usuario
                            <select class="action-card__select" id="tipo" name="tipo">
                                <?php $t = isset($inputs['tipo']) ? $inputs['tipo'] : ''; ?>
                                <option value="" <?php echo ($t === '') ? 'selected' : ''; ?> disabled>Seleccione...</option>
                                <option value="0" <?php echo ($t === '0') ? 'selected' : ''; ?>>Estudiante / Paciente</option>
                                <option value="1" <?php echo ($t === '1') ? 'selected' : ''; ?>>Enfermero</option>
                                <option value="2" <?php echo ($t === '2') ? 'selected' : ''; ?>>Médico</option>
                                <option value="3" <?php echo ($t === '3') ? 'selected' : ''; ?>>Director / Autoridad</option>
                            </select>
                        </label>
                    <label for="nombre" class="action-card__label">Nombres
                        <input type="text" class="action-card__input" id="nombre" name="nombre" value="<?php echo isset($inputs['nombre']) ? $inputs['nombre'] : ''; ?>" required>
                    </label>
                    <label for="apellido" class="action-card__label">Apellidos
                        <input type="text" class="action-card__input" id="apellido" name="apellido" value="<?php echo isset($inputs['apellido']) ? $inputs['apellido'] : ''; ?>" required>
                    </label>
                    <label for="pnf" class="action-card__label">PNF (Área académica)
                        <select class="action-card__select" id="pnf" name="pnf" required>
                            <?php $p = isset($inputs['pnf']) ? $inputs['pnf'] : ''; ?>
                            <option value="" <?php echo ($p === '') ? 'selected' : ''; ?> disabled>Seleccione PNF...</option>
                            <option value="1" <?php echo ($p === '1') ? 'selected' : ''; ?>>Informática</option>
                            <option value="2" <?php echo ($p === '2') ? 'selected' : ''; ?>>Administración</option>
                            <option value="3" <?php echo ($p === '3') ? 'selected' : ''; ?>>Higiene y Seguridad</option>
                        </select>
                    </label>
                    <label for="fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                        <input type="date" class="action-card__input" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo isset($inputs['fecha_nacimiento']) ? $inputs['fecha_nacimiento'] : ''; ?>" required>
                    </label>
                    <label for="tlfprincipal" class="action-card__label">Teléfono Principal
                        <input type="text" class="action-card__input" id="tlfprincipal" name="tlfprincipal" value="<?php echo isset($inputs['tlfprincipal']) ? $inputs['tlfprincipal'] : ''; ?>" required>
                    </label>
                    <label for="tlfemergencia" class="action-card__label">Contacto de Emergencia
                        <input type="text" class="action-card__input" id="tlfemergencia" name="tlfemergencia" value="<?php echo isset($inputs['tlfemergencia']) ? $inputs['tlfemergencia'] : ''; ?>" required>
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
                    <button type="reset" class="action-card__button">Limpiar Formulario</button>
                    <button type="submit" class="action-card__button">Guardar en Sistema</button>
                    </div>
                </form>
            </div>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-usuario">
            <p>aaaaaaaaaaaaactualizar</p>
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-usuario">
            <p>aaaaaaaaaaaabuscar</p>
        </dialog>
        
    </main>
    <footer>

    </footer>
</body>
</html>