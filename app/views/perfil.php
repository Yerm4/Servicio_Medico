<?php
use app\controller\Controller;
if ($_SESSION["cedula"]) {
    $controller = new Controller($pdo);
    $usuariosEncontrados = [];
    if ($tieneGestionarUsuarios) {
        $usuariosEncontrados = $controller->consultar();
    }
} else {
    header("Location: login");
}
$inputs = isset($_SESSION['inputs']) ? $_SESSION['inputs'] : [];
unset($_SESSION['inputs']);

$roles = [];
$permisos = [];
$rolePermMap = [];
if ($tieneGestionarRolesPermisos) {
    $roles = $userModel->obtenerRoles();
    $permisos = $userModel->obtenerPermisos();
    $rolesPermisos = $userModel->obtenerRolesPermisos();
    foreach ($rolesPermisos as $rp) {
        $rolePermMap[$rp['id_rol']][$rp['id_permiso']] = true;
    }
}
$consultasRecientes = [];
$misConsultas = [];
$consultaModel = new \app\model\Consulta($pdo);

if ($paginaActual === 'consultas' && $tieneVerConsultas) {
    $consultasRecientes = $consultaModel->obtenerConsultasRecientes(20);
}

if ($paginaActual === 'perfil' && !$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos) {
    $misConsultas = $consultaModel->obtenerConsultasPorPaciente($_SESSION["cedula"]);
}
?>
<main class="perfil">    
        <aside class="side-menu">
        <h1>Menu</h1>
            <hr>
            <?php if (!$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos): ?>
            <a href="perfil" id="historial" class="<?= $paginaActual === 'perfil' ? 'focus' : '' ?>">Mi Historial</a>
            <?php endif; ?>
            <?php if ($tieneGestionarUsuarios): ?>
            <a href="usuarios" id="usuario" class="<?= $paginaActual === 'usuarios' ? 'focus' : '' ?>">Usuarios</a>
            <?php endif; ?>
            <?php if ($tieneVerConsultas): ?>
            <a href="consultas" id="consulta" class="<?= $paginaActual === 'consultas' ? 'focus' : '' ?>">Consultas</a>
            <?php endif; ?>
            <?php if ($tieneGestionarRolesPermisos): ?>
            <a href="configuracion" id="configuracion" class="<?= $paginaActual === 'configuracion' ? 'focus' : '' ?>">Configuración</a>
            <?php endif; ?>
        </aside>

        <section class="section-1 section-1--perfil">
            
            <div class="buscador-caja">
                <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
                <input type="text" id="inputBuscarUsuario" placeholder="Buscar por cédula o nombre" class="action-card__input" autocomplete="off" style="margin-bottom: 15px;">
                <?php endif; ?>
                <?php if ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
                <input type="text" id="inputBuscarConsulta" placeholder="Buscar consulta por paciente, médico, cédula o motivo" class="action-card__input" autocomplete="off" style="width: 500px; max-width: 100%; margin-bottom: 15px;">
                <?php endif; ?>
                
                <div class="section-1__box transition" id="section-1-box">
                    <?php if ($paginaActual === 'usuarios' && $tieneGestionarUsuarios): ?>
                        <a name="openModal" data-modal="modalRegistrarUsuario" class="action-card__button" href="">Registrar usuario</a>
                    <?php elseif ($paginaActual === 'consultas' && $tieneVerConsultas && $tieneRealizarConsulta): ?>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; width: 100%;">
                            <a name="openModal" data-modal="modalRegistrarConsulta" class="action-card__button action-card__button--grid-principal" href="#" style="max-width: 250px;">Iniciar consulta</a>
                        </div>
                    <?php elseif ($paginaActual === 'configuracion' && $tieneGestionarRolesPermisos): ?>
                        <span style="font-weight: bold;">Configuración del Sistema</span>
                    <?php elseif ($paginaActual === 'sesion'): ?>
                        <a href="logout" style="color: blue; font-weight: bold; font-size: 1.25rem; text-decoration: underline;">Cerrar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
            

            <?php if($paginaActual === 'usuarios' && $tieneGestionarUsuarios && $usuariosEncontrados): ?>

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
                    <td class="tabla-usuarios__desc"><?= e($registro["nombre_tipo"] ?? ($registro["tipo"] === 0 ? "Estudiante" : "Docente")) ?></td>
                    <td class="tabla-usuarios__desc"><?= calcularEdad($registro["fecha_nacimiento"])?></td>
                    <td class="tabla-usuarios__desc"><?= $registro["sexo"] == 1 ? "Masculino" : "Femenino" ?></td>
                    <td class="tabla-usuarios__desc"> <?=e($registro["tlfprincipal"])?></td>
                    <td class="tabla-usuarios__desc"> <button class="editar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Actualizar</button></td>
                    <td class="tabla-usuarios__desc"> <button class="eliminar-usuario action-card__button" data-id="<?= e($registro["cedula"]) ?>">Eliminar</button></td>
                </tr>
                <?php endforeach?>
                </tbody>
            </table>

            <?php endif?>

            <?php if ($paginaActual === 'consultas' && $tieneVerConsultas): ?>
                <div style="width: 100%; margin-top: 20px;">
                    <h3 style="margin-bottom: 15px; text-align: center;">Consultas Recientes</h3>
                    <table id="tablaRegistrosConsultas" style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <thead>
                            <tr style="border-bottom: 2px solid #ddd; background: #fafafa;">
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Fecha</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Paciente</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Médico</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Motivo</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Síntomas</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Diagnóstico (CIE-10)</th>
                                <th style="padding: 10px; text-align: left; font-size: 0.9em;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaConsultas">
                            <?php if (!empty($consultasRecientes)): ?>
                                <?php foreach ($consultasRecientes as $c): ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px; font-size: 0.9em; white-space: nowrap;"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;">
                                            <strong><?= e(($c['paciente_nombre'] ?? '') . ' ' . ($c['paciente_apellido'] ?? '')) ?></strong>
                                            <div style="font-size: 0.8em; color: #666;">C.I. <?= e($c['id_usuario']) ?></div>
                                        </td>
                                        <td style="padding: 10px; font-size: 0.9em;"><?= e(($c['medico_nombre'] ?? '') . ' ' . ($c['medico_apellido'] ?? '')) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;"><?= e($c['motivo_de_visita']) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;">
                                            <?= !empty($c['sintomas']) ? e(implode(', ', $c['sintomas'])) : '<span style="color: #999;">Ninguno</span>' ?>
                                        </td>
                                        <td style="padding: 10px; font-size: 0.9em;">
                                            <?php if (!empty($c['diagnosticos'])): ?>
                                                <?php foreach ($c['diagnosticos'] as $diag): ?>
                                                    <div style="margin-bottom: 2px;">
                                                        <strong style="color: #b91c1c;"><?= e($diag['codigo_icd_diagnostico']) ?></strong> - <?= e($diag['patologia'] ?? 'Sin detalle') ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span style="color: #999;">Sin diagnóstico</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 10px; font-size: 0.9em; display: flex; gap: 5px;">
                                            <button class="ver-detalles-consulta action-card__button" data-id="<?= e($c['id']) ?>" style="background: #4a5568; color: #fff;">Ver detalles</button>
                                            <?php if ($tieneModificarConsulta): ?>
                                                <button class="editar-consulta action-card__button" data-id="<?= e($c['id']) ?>">Actualizar</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #666;">No hay ninguna consulta asociada a ese usuario.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div style="text-align: center; margin-top: 15px;">
                        <button id="btnCargarMasConsultas" class="action-card__button" style="display: none; width: auto; margin: 0 auto;">Cargar más</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($paginaActual === 'perfil' && !$tieneGestionarUsuarios && !$tieneVerConsultas && !$tieneGestionarRolesPermisos): ?>
                <?php if (!empty($misConsultas)): ?>
                    <div style="width: 100%; margin-top: 20px;">
                        <h3 style="margin-bottom: 15px; text-align: center;">Mi Historial Médico</h3>
                        <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                            <thead>
                                <tr style="border-bottom: 2px solid #ddd; background: #fafafa;">
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Médico Tratante</th>
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Motivo</th>
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Síntomas</th>
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Diagnóstico</th>
                                    <th style="padding: 10px; text-align: left; font-size: 0.9em;">Tratamiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($misConsultas as $c): ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px; font-size: 0.9em; white-space: nowrap;"><?= e(date('d/m/Y H:i', strtotime($c['fecha_consulta']))) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;"><?= e(($c['medico_nombre'] ?? '') . ' ' . ($c['medico_apellido'] ?? '')) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;"><?= e($c['motivo_de_visita']) ?></td>
                                        <td style="padding: 10px; font-size: 0.9em;">
                                            <?= !empty($c['sintomas']) ? e(implode(', ', $c['sintomas'])) : '<span style="color: #999;">Ninguno</span>' ?>
                                        </td>
                                        <td style="padding: 10px; font-size: 0.9em;">
                                            <?php if (!empty($c['diagnosticos'])): ?>
                                                <?php foreach ($c['diagnosticos'] as $diag): ?>
                                                    <div style="margin-bottom: 2px;">
                                                        <strong style="color: #b91c1c;"><?= e($diag['codigo_icd_diagnostico']) ?></strong> - <?= e($diag['patologia'] ?? 'Sin detalle') ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span style="color: #999;">Sin diagnóstico</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 10px; font-size: 0.9em;"><?= e($c['medicamento_suministrado'] ?: 'Ninguno') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px; width: 100%;">
                        <p style="color: #666; margin: 0; font-size: 1.1em;">No hay consultas médicas asociadas a este usuario.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($paginaActual === 'configuracion' && $tieneGestionarRolesPermisos): ?>
            <div id="seccion-configuracion" class="configuracion-container" style="display: block; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px;">
                <div class="nested-tabs-menu" style="display: flex; gap: 15px; border-bottom: 2px solid #eee; margin-bottom: 25px; padding-bottom: 10px;">
                    <a href="#" id="sub-tab-general" class="sub-tab-link active" style="font-weight: bold; text-decoration: none; color: #333; border-bottom: 3px solid blue; padding-bottom: 10px;">General</a>
                    <a href="#" id="sub-tab-roles" class="sub-tab-link" style="font-weight: bold; text-decoration: none; color: #777; padding-bottom: 10px;">Roles y Permisos</a>
                </div>

                <div id="sub-content-general" class="sub-tab-content" style="display: block;">
                    <div style="background: #fdfdfd; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0; margin-bottom: 15px;">Configuración General</h3>
                        <form action="index.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="guardar_configuracion">
                            
                            <label style="display: flex; flex-direction: column; gap: 5px; max-width: 350px;">
                                <span style="font-weight: bold; font-size: 0.9em;">Rol por defecto en registro</span>
                                <select name="rol_defecto" required class="action-card__select" style="width: 100%;">
                                    <?php 
                                        $currentDefRol = $userModel->obtenerRolDefecto();
                                        foreach ($roles as $role): 
                                            $sel = ($role['id_rol'] == $currentDefRol) ? 'selected' : '';
                                    ?>
                                        <option value="<?= e($role['id_rol']) ?>" <?= $sel ?>><?= e($role['nombre_rol']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            
                            <div style="text-align: left; margin-top: 10px;">
                                <button type="submit" class="action-card__button" style="width: auto;">Guardar Configuración</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="sub-content-roles" class="sub-tab-content" style="display: none;">
                    <div style="background: #fdfdfd; border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                        <h3 style="margin-top: 0; margin-bottom: 15px;">Crear Nuevo Rol</h3>
                        <form action="index.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="registrar_rol">
                            
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <label style="flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 5px;">
                                    <span style="font-weight: bold; font-size: 0.9em;">Nombre del Rol</span>
                                    <input type="text" name="nombre_rol" required class="action-card__input" placeholder="Ej. Enfermero" style="width: 100%;">
                                </label>
                                
                                <label style="flex: 2; min-width: 300px; display: flex; flex-direction: column; gap: 5px;">
                                    <span style="font-weight: bold; font-size: 0.9em;">Descripción</span>
                                    <input type="text" name="descripcion_rol" class="action-card__input" placeholder="Ej. Personal de soporte médico" style="width: 100%;">
                                </label>
                            </div>
                            
                            <div style="text-align: right;">
                                <button type="submit" class="action-card__button" style="width: auto;">Crear Rol</button>
                            </div>
                        </form>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

                    <div style="margin-bottom: 30px;">
                        <h3 style="margin-top: 0; margin-bottom: 15px;">Roles Registrados</h3>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <thead>
                                <tr style="border-bottom: 2px solid #ddd; background: #fafafa;">
                                    <th style="text-align: left; padding: 10px; font-size: 0.9em;">ID</th>
                                    <th style="text-align: left; padding: 10px; font-size: 0.9em;">Nombre del Rol</th>
                                    <th style="text-align: left; padding: 10px; font-size: 0.9em;">Descripción</th>
                                    <th style="text-align: center; padding: 10px; font-size: 0.9em;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $currentDefRol = $userModel->obtenerRolDefecto();
                                    foreach ($roles as $role): 
                                ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px;"><?= e($role['id_rol']) ?></td>
                                        <td style="padding: 10px;"><strong><?= e($role['nombre_rol']) ?></strong></td>
                                        <td style="padding: 10px;"><?= e($role['descripcion_rol']) ?></td>
                                        <td style="padding: 10px; text-align: center; display: flex; justify-content: center; gap: 10px;">
                                            <button type="button" class="action-card__button editar-rol" data-id="<?= e($role['id_rol']) ?>" data-nombre="<?= e($role['nombre_rol']) ?>" data-descripcion="<?= e($role['descripcion_rol']) ?>" style="padding: 5px 10px; font-size: 0.85em; width: auto; height: auto;">Editar</button>
                                            <form action="index.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este rol? Se reasignarán los usuarios asociados al rol por defecto.');" style="margin: 0; display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <input type="hidden" name="form" value="eliminar_rol">
                                                <input type="hidden" name="id_rol" value="<?= e($role['id_rol']) ?>">
                                                <button type="submit" class="action-card__button action-card__button--red" style="padding: 5px 10px; font-size: 0.85em; width: auto; height: auto;">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

                    <h3 style="margin-top: 0; margin-bottom: 15px;">Matriz de Asignación de Permisos</h3>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="form" value="guardar_roles_permisos">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid #ddd;">
                                    <th style="text-align: left; padding: 10px;">Permiso / Descripción</th>
                                    <?php foreach ($roles as $role): ?>
                                        <th style="padding: 10px; text-align: center;"><?= e($role['nombre_role'] ?? $role['nombre_rol']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permisos as $perm): ?>
                                    <tr style="border-bottom: 1px solid #eee;">
                                        <td style="padding: 10px; text-align: left;">
                                            <div style="font-weight: bold;"><?= e($perm['nombre_permiso']) ?></div>
                                            <div style="font-size: 0.85em; color: #666;"><?= e($perm['descripcion_permiso']) ?></div>
                                        </td>
                                        <?php foreach ($roles as $role): ?>
                                            <?php 
                                                $checked = isset($rolePermMap[$role['id_rol']][$perm['id_permiso']]) ? 'checked' : '';
                                            ?>
                                            <td style="text-align: center; padding: 10px;">
                                                <input type="checkbox" name="permisos[<?= $role['id_rol'] ?>][]" value="<?= $perm['id_permiso'] ?>" <?= $checked ?>>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="margin-top: 20px; text-align: right;">
                            <button type="submit" class="action-card__button">Guardar Matriz de Permisos</button>
                        </div>
                    </form>
                </div>
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
        <?php if ($tieneGestionarUsuarios): ?>
        <dialog id="modalRegistrarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarUsuario.php" ?>
        </dialog>
        <dialog id="modalActualizarUsuario" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarUsuario.php" ?>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneVerConsultas): ?>
        <dialog id="modalRegistrarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalRegistrarConsulta.php" ?>
        </dialog>
        <dialog id="modalActualizarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalActualizarConsulta.php" ?>
        </dialog>
        <dialog id="modalBuscarConsulta" class="modal-crud">
            <?php include_once __DIR__."/modalBuscarConsulta.php" ?>
        </dialog>
        <?php endif; ?>
        <?php if ($tieneGestionarRolesPermisos): ?>
        <dialog id="modalEditarRol" class="modal-crud">
            <div class="action-card">
                <h3 class="action-card__title">Editar Rol</h3>
                <form action="index.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="actualizar_rol">
                    <input type="hidden" name="id_rol" id="edit_id_rol">
                    
                    <label class="action-card__label" style="display: flex; flex-direction: column; gap: 5px;">
                        <span style="font-weight: bold; font-size: 0.9em;">Nombre del Rol</span>
                        <input type="text" name="nombre_rol" id="edit_nombre_rol" required class="action-card__input">
                    </label>
                    
                    <label class="action-card__label" style="display: flex; flex-direction: column; gap: 5px;">
                        <span style="font-weight: bold; font-size: 0.9em;">Descripción</span>
                        <input type="text" name="descripcion_rol" id="edit_descripcion_rol" class="action-card__input">
                    </label>
                    
                    <div class="action-card__button-grid" style="margin-top: 10px;">
                        <button type="submit" class="action-card__button">Guardar Cambios</button>
                    </div>
                </form>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalEditarRol" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        <?php endif; ?>
        <script>
            const ES_MEDICO_O_DIRECTOR = <?= $tieneModificarConsulta ? 'true' : 'false' ?>;
        </script>

        <dialog id="modalVerDetallesConsulta" class="modal-crud">
            <div class="action-card" style="max-width: 600px;">
                <h3 class="action-card__title">Detalles de la Consulta Médica</h3>
                
                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px; text-align: left; background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
                    <div><strong>Fecha:</strong> <span id="det_fecha"></span></div>
                    <div><strong>Paciente:</strong> <span id="det_paciente"></span></div>
                    <div><strong>Médico Tratante:</strong> <span id="det_medico"></span></div>
                    <div><strong>Motivo de Visita:</strong> <span id="det_motivo"></span></div>
                    <div><strong>Observaciones:</strong> <span id="det_observaciones"></span></div>
                    <div><strong>Medicamento Suministrado:</strong> <span id="det_medicamento"></span></div>
                    <div><strong>Síntomas:</strong> <span id="det_sintomas"></span></div>
                    <div><strong>Diagnósticos (CIE-10):</strong> <div id="det_diagnosticos" style="margin-top: 5px; padding-left: 10px; border-left: 3px solid #b91c1c;"></div></div>
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="action-card__button" onclick="const m = document.getElementById('modalVerDetallesConsulta'); m.style.opacity = 0; setTimeout(() => m.close(), 500);" style="width: auto;">Cerrar</button>
                </div>
            </div>
            <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalVerDetallesConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"/>
            </svg>
        </dialog>
        
    </main>
    <footer>
        <script src="assets/script/append.js" defer></script>
        <script src="assets/script/eliminar.js" defer></script>
        <script src="assets/script/consultarTabla.js" defer></script>
    </footer>
</body>
</html>