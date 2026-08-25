<?php if ($paginaActual === 'configuracion' && ($tieneGestionarRolesPermisos || $tieneGestionarCondiciones)): ?>
            <div id="seccion-configuracion" class="configuracion-container seccion-configuracion-box">
                <div class="nested-tabs-menu menu-subtabs">
                    <?php if ($tieneGestionarRolesPermisos): ?>
                    <a href="#" id="sub-tab-general" class="sub-tab-link subtab-link-comun subtab-link-general">General</a>
                    <a href="#" id="sub-tab-roles" class="sub-tab-link subtab-link-comun subtab-link-roles">Roles y Permisos</a>
                    <?php endif; ?>
                    <?php if ($tieneGestionarCondiciones): ?>
                    <a href="#" id="sub-tab-condiciones" class="sub-tab-link subtab-link-comun subtab-link-condiciones" style="<?= !$tieneGestionarRolesPermisos ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">Condiciones</a>
                    <?php endif; ?>
                </div>

                <?php if ($tieneGestionarRolesPermisos): ?>
                    <div id="sub-content-general" class="sub-tab-content subcontent-general-box configuracion-bloque-formulario">
                        <h3 class="titulo-configuracion-interna">Configuración General</h3>
                        <form action="index.php" method="POST" class="form-configuracion-flex">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="guardar_configuracion">
                            
                            <label class="label-rol-defecto">
                                <span class="texto-label-negrita">Rol por defecto en registro</span>
                                <select name="rol_defecto" required class="action-card__select select-rol-defecto">
                                    <?php 
                                        $currentDefRol = $userModel->obtenerRolDefecto();
                                        foreach ($roles as $role): 
                                            $sel = ($role['id_rol'] == $currentDefRol) ? 'selected' : '';
                                    ?>
                                        <option value="<?= e($role['id_rol']) ?>" <?= $sel ?>><?= e($role['nombre_rol']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            
                            <div class="contenedor-btn-izq">
                                <button type="submit" class="action-card__button btn-guardar-config">Guardar Configuración</button>
                            </div>
                        </form>
                    </div>

                <div id="sub-content-roles" class="sub-tab-content subcontent-roles-box">
                    <div class="contenedor-form-nuevo-rol">
                        <h3 class="titulo-configuracion-interna">Crear Nuevo Rol</h3>
                        <form action="index.php" method="POST" class="form-configuracion-flex">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="form" value="registrar_rol">
                            
                            <div class="fila-inputs-rol">
                                <label class="col-nombre-rol">
                                    <span class="texto-label-negrita">Nombre del Rol</span>
                                    <input type="text" name="nombre_rol" required class="action-card__input input-ancho-total" placeholder="Ej. Enfermero">
                                </label>
                                
                                <label class="col-descripcion-rol">
                                    <span class="texto-label-negrita">Descripción</span>
                                    <input type="text" name="descripcion_rol" class="action-card__input input-ancho-total" placeholder="Ej. Personal de soporte médico">
                                </label>
                            </div>
                            
                            <div class="contenedor-btn-der">
                                <button type="submit" class="action-card__button btn-crear-rol">Crear Rol</button>
                            </div>
                        </form>
                    </div>

                    <hr class="separador-configuracion">

                    <div class="contenedor-roles-registrados">
                        <h3 class="titulo-configuracion-interna">Roles Registrados</h3>
                        <table class="tabla-consultas">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="tabla-roles-cabecera-izq">ID</th>
                                    <th class="tabla-roles-cabecera-izq">Nombre del Rol</th>
                                    <th class="tabla-roles-cabecera-izq">Descripción</th>
                                    <th class="tabla-roles-cabecera-centro">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $currentDefRol = $userModel->obtenerRolDefecto();
                                    foreach ($roles as $role): 
                                ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-roles-comun"><?= e($role['id_rol']) ?></td>
                                        <td class="td-roles-comun"><strong><?= e($role['nombre_rol']) ?></strong></td>
                                        <td class="td-roles-comun"><?= e($role['descripcion_rol']) ?></td>
                                        <td class="td-roles-acciones">
                                            <button type="button" class="action-card__button editar-rol btn-editar-rol-lista" data-id="<?= e($role['id_rol']) ?>" data-nombre="<?= e($role['nombre_rol']) ?>" data-descripcion="<?= e($role['descripcion_rol']) ?>">Editar</button>
                                            <form action="index.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este rol? Se reasignarán los usuarios asociados al rol por defecto.');" class="form-eliminar-inline">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <input type="hidden" name="form" value="eliminar_rol">
                                                <input type="hidden" name="id_rol" value="<?= e($role['id_rol']) ?>">
                                                <button type="submit" class="action-card__button action-card__button--red btn-eliminar-rol-lista">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr class="separador-configuracion">

                    <h3 class="titulo-configuracion-interna">Matriz de Asignación de Permisos</h3>
                    <form action="index.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="form" value="guardar_roles_permisos">
                        <table class="tabla-consultas">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="th-matriz-permiso-desc">Permiso / Descripción</th>
                                    <?php foreach ($roles as $role): ?>
                                        <th class="th-matriz-rol"><?= e($role['nombre_role'] ?? $role['nombre_rol']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permisos as $perm): ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-matriz-desc">
                                            <div class="texto-configuracion-sistema"><?= e($perm['nombre_permiso']) ?></div>
                                            <div class="texto-matriz-permiso-sub"><?= e($perm['descripcion_permiso']) ?></div>
                                        </td>
                                        <?php foreach ($roles as $role): ?>
                                            <?php 
                                                $checked = isset($rolePermMap[$role['id_rol']][$perm['id_permiso']]) ? 'checked' : '';
                                            ?>
                                            <td class="td-matriz-checkbox">
                                                <input type="checkbox" name="permisos[<?= $role['id_rol'] ?>][]" value="<?= $perm['id_permiso'] ?>" <?= $checked ?>>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="contenedor-btn-der class-margin-top-20"> <button type="submit" class="action-card__button">Guardar Matriz de Permisos</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <?php if ($tieneGestionarCondiciones): ?>
                <div id="sub-content-condiciones" class="sub-tab-content subcontent-condiciones-box" style="display: <?= $tieneGestionarRolesPermisos ? 'none' : 'block' ?>;">
                    <div class="contenedor-roles-registrados">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <h3 class="titulo-configuracion-interna" style="margin:0;">Condiciones Registradas</h3>
                            <button type="button" class="action-card__button" name="openModal" data-modal="modalRegistrarCondicion">Registrar Condición</button>
                        </div>
                        <div style="margin-bottom:15px;">
                            <input type="text" id="inputBuscarCondicion" placeholder="Buscar condición por nombre o descripción..." class="action-card__input input-ancho-total" autocomplete="off" style="width:100%; box-sizing:border-box;">
                        </div>
                        <table class="tabla-consultas" id="tablaCondiciones">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="tabla-roles-cabecera-izq">ID</th>
                                    <th class="tabla-roles-cabecera-izq">Nombre de la Condición</th>
                                    <th class="tabla-roles-cabecera-izq">Descripción</th>
                                    <th class="tabla-roles-cabecera-centro">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaCondiciones">
                                <?php if (empty($condicionesRegistradas)): ?>
                                    <tr><td colspan="4" class="td-tabla-vacia">No hay condiciones registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($condicionesRegistradas as $cond): ?>
                                        <tr class="tr-body-consultas">
                                            <td class="td-roles-comun"><?= e($cond['id']) ?></td>
                                            <td class="td-roles-comun"><strong><?= e($cond['nombre_condicion']) ?></strong></td>
                                            <td class="td-roles-comun"><?= e($cond['descripcion_condicion']) ?></td>
                                            <td class="td-roles-acciones">
                                                <button type="button" class="action-card__button editar-condicion btn-editar-rol-lista" data-id="<?= e($cond['id']) ?>" data-nombre="<?= e($cond['nombre_condicion']) ?>" data-descripcion="<?= e($cond['descripcion_condicion']) ?>">Editar</button>
                                                <form action="index.php" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta condición? Se eliminará de todos los usuarios asociados.');" class="form-eliminar-inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <input type="hidden" name="form" value="eliminar_condicion">
                                                    <input type="hidden" name="id" value="<?= e($cond['id']) ?>">
                                                    <button type="submit" class="action-card__button action-card__button--red btn-eliminar-rol-lista">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>