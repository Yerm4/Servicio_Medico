<?php
if (!isset($_SESSION["cedula"])) {
    header("Location: login");
    exit;
}

$titulo = "Configuración";
include_once __DIR__."/layout/header.php";
?>

<main class="perfil">    
    <?php include_once __DIR__."/layout/sidebar.php"; ?>

    <section class="section-1 section-1--perfil">
<div id="seccion-configuracion" class="configuracion-container seccion-configuracion-box">
    <div class="nested-tabs-menu menu-subtabs">
        <?php if ($tieneGestionarRolesPermisos): ?>
            <a href="#" id="sub-tab-general" class="sub-tab-link subtab-link-comun subtab-link-general">General</a>
            <a href="#" id="sub-tab-roles" class="sub-tab-link subtab-link-comun subtab-link-roles">Roles y Permisos</a>
        <?php endif; ?>
        <?php if ($tieneGestionarCondiciones): ?>
            <a href="#" id="sub-tab-condiciones" class="sub-tab-link subtab-link-comun subtab-link-condiciones" style="<?= !$tieneGestionarRolesPermisos ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">Condiciones</a>
        <?php endif; ?>
        <?php if ($tieneGestionarOferta): ?>        
            <a href="#" id="sub-tab-nucleos" class="sub-tab-link subtab-link-comun subtab-link-nucleos" style="<?= !$tieneGestionarOferta ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">Nucleos</a>
            <a href="#" id="sub-tab-pnf" class="sub-tab-link subtab-link-comun subtab-link-pnf" style="<?= !$tieneGestionarOferta ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">PNFs</a>
            <a href="#" id="sub-tab-ofertas" class="sub-tab-link subtab-link-comun subtab-link-ofertas" style="<?= !$tieneGestionarOferta ? 'color:#333; border-bottom:3px solid blue;' : '' ?>">Ofertas</a>
        <?php endif; ?>
        
    </div>

    <?php if ($tieneGestionarRolesPermisos): ?>
        <!-- Pestaña Configuración General -->
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
                <button type="submit" class="action-card__button btn-guardar-config">Guardar Configuración</button>
            </form>
        </div>

        <!-- Pestaña Roles y Permisos -->
        <div id="sub-content-roles" class="sub-tab-content subcontent-roles-box">
            <div class="contenedor-form-nuevo-rol">
                <h3 class="titulo-configuracion-interna">Crear Nuevo Rol</h3>
                <form action="index.php" method="POST" class="form-configuracion-flex">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="form" value="registrar_rol">
                    <input type="text" name="nombre_rol" required class="action-card__input" placeholder="Ej. Enfermero">
                    <input type="text" name="descripcion_rol" class="action-card__input" placeholder="Ej. Personal médico">
                    <button type="submit" class="action-card__button btn-crear-rol">Crear Rol</button>
                </form>
            </div>

            <hr class="separador-configuracion">

            <div class="contenedor-roles-registrados">
                <h3 class="titulo-configuracion-interna">Roles Registrados</h3>
                <table class="tabla-consultas">
                    <thead>
                        <tr class="tr-head-consultas">
                            <th>ID</th>
                            <th>Nombre del Rol</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                            <tr class="tr-body-consultas">
                                <td><?= e($role['id_rol']) ?></td>
                                <td><strong><?= e($role['nombre_rol']) ?></strong></td>
                                <td><?= e($role['descripcion_rol']) ?></td>
                                <td>
                                    <button type="button" class="action-card__button editar-rol" data-id="<?= e($role['id_rol']) ?>" data-nombre="<?= e($role['nombre_rol']) ?>" data-descripcion="<?= e($role['descripcion_rol']) ?>">Editar</button>
                                    <form action="index.php" method="POST" class="form-eliminar-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="form" value="eliminar_rol">
                                        <input type="hidden" name="id_rol" value="<?= e($role['id_rol']) ?>">
                                        <button type="submit" class="action-card__button action-card__button--red">Eliminar</button>
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
                            <th>Permiso / Descripción</th>
                            <?php foreach ($roles as $role): ?>
                                <th><?= e($role['nombre_role'] ?? $role['nombre_rol']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($permisos)): ?>
                            <?php foreach ($permisos as $perm): ?>
                                <tr class="tr-body-consultas">
                                    <td>
                                        <div><?= e($perm['nombre_permiso']) ?></div>
                                        <small><?= e($perm['descripcion_permiso']) ?></small>
                                    </td>
                                    <?php foreach ($roles as $role): ?>
                                        <td>
                                            <input type="checkbox" name="permisos[<?= $role['id_rol'] ?>][]" value="<?= $perm['id_permiso'] ?>" <?= isset($rolePermMap[$role['id_rol']][$perm['id_permiso']]) ? 'checked' : '' ?>>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="submit" class="action-card__button">Guardar Matriz de Permisos</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($tieneGestionarCondiciones): ?>
        <!-- Pestaña Condiciones Médicas -->
        <div id="sub-content-condiciones" class="sub-tab-content subcontent-condiciones-box" style="display: <?= $tieneGestionarRolesPermisos ? 'none' : 'block' ?>;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3 class="titulo-configuracion-interna">Condiciones Registradas</h3>
                <button type="button" class="action-card__button" name="openModal" data-modal="modalRegistrarCondicion">Registrar Condición</button>
            </div>
            <table class="tabla-consultas" id="tablaCondiciones">
                <thead>
                    <tr class="tr-head-consultas">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaCondiciones">
                    <?php if (empty($condicionesRegistradas)): ?>
                        <tr><td colspan="4" class="td-tabla-vacia">No hay condiciones registradas.</td></tr>
                    <?php else: foreach ($condicionesRegistradas as $cond): ?>
                        <tr class="tr-body-consultas">
                            <td><?= e($cond['id']) ?></td>
                            <td><strong><?= e($cond['nombre_condicion']) ?></strong></td>
                            <td><?= e($cond['descripcion_condicion']) ?></td>
                            <td>
                                <button type="button" class="action-card__button editar-condicion" data-id="<?= e($cond['id']) ?>" data-nombre="<?= e($cond['nombre_condicion']) ?>" data-descripcion="<?= e($cond['descripcion_condicion']) ?>">Editar</button>
                                <form action="index.php" method="POST" class="form-eliminar-inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="form" value="eliminar_condicion">
                                    <input type="hidden" name="id" value="<?= e($cond['id']) ?>">
                                    <button type="submit" class="action-card__button action-card__button--red">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php if ($tieneGestionarOferta): ?>
        <div id="sub-content-oferta" class="sub-tab-content subcontent-oferta-box" style="display: <?= $tieneGestionarOferta ? 'none' : 'block' ?>;">
             <div class="contenedor-tabla-consultas" style="margin-top: 1.5rem; width: 100%;">
            <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 class="titulo-tabla-consultas" style="margin: 0;">Ofertas Académicas</h3>
                <a name="openModal" data-modal="modalRegistrarOferta" class="action-card__button" href="#" style="cursor: pointer;">Registrar Oferta Academica</a>
            </div>
            <div id="alert-container-oferta" style="margin-bottom: 1rem;"></div>
            <table id="tablaRegistrosOfertas" class="tabla-consultas" style="width: 100%;">
                <thead>
                    <tr class="tr-head-consultas">
                        <th class="th-consultas" style="text-align: left;">Núcleo</th>
                        <th class="th-consultas" style="text-align: left;">Programa de Formación Nacional (PNF)</th>
                        <th class="th-consultas" style="text-align: left;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaOfertas">
                    <?php if (!empty($ofertas)): foreach ($ofertas as $o): ?>
                        <tr class="tr-body-consultas">
                            <td class="td-consultas"><strong><?= e($o['nombre_nucleo']) ?></strong></td>
                            <td class="td-consultas"><?= e($o['nombre_pnf']) ?></td>
                            <td class="td-acciones-btn">
                                <form id="eliminarOferta" method="POST" action="index.php" style="display:inline; margin:0;">
                                    <input type="hidden" name="id_oferta" value="<?= e($o['id_oferta'] ?? $o['id'] ?? '') ?>">
                                    <input type="hidden" name="id_nucleo" value="<?= e($o['id_nucleo']) ?>">
                                    <input type="hidden" name="id_pnf" value="<?= e($o['id_pnf']) ?>">
                                    <button type="submit" data-id="<?= e($o['id_oferta'] ?? $o['id'] ?? '') ?>" class="action-card__button" style="background-color: #d9534f; cursor: pointer;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
        <div id="sub-content-nucleos" class="sub-tab-content subcontent-general-box configuracion-bloque-formulario" style="display: <?= $tieneGestionarOferta ? 'none' : 'block' ?>;">
            <div id="sedes-carreras" class="contenedor-sedes-carreras" style="margin-top: 1.5rem; width: 100%; display: flex; flex-direction: column;">
                <!-- Tabla Núcleos -->
                <div class="contenedor-tabla-consultas" style="width: 100%;">
                    <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="titulo-tabla-consultas" style="margin: 0;">Núcleos</h3>
                        <a name="openModal" data-modal="modalRegistrarNucleo" class="action-card__button" href="#" style="width: fit-content; display: inline-block;">Registrar Nucleo</a>
                    </div>
                    <div id="alert-container-nucleo" style="margin-bottom: 1rem;"></div>
                    <table id="tablaNucleos" class="tabla-consultas" style="width: 100%;">
                        <thead>
                            <tr class="tr-head-consultas">
                                <th class="th-consultas" style="text-align: left;">ID</th>
                                <th class="th-consultas" style="text-align: left;">Nombre del Núcleo</th>
                                <th class="th-consultas" style="text-align: left;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaNucleos">
                            <?php 
                            if (!empty($nucleos)): 
                                $i = 1;
                                foreach ($nucleos as $n): 
                            ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-consultas-nowrap" style="text-align: left;"><?= $i++ ?></td>
                                        <td class="td-consultas" style="text-align: left;"><strong><?= e($n['nombre_nucleo']) ?></strong></td>
                                        
                                        <td class="td-acciones-btn" style="text-align: left;">
                                            <div style="display: flex; gap: 10px; justify-content: flex-start; align-items: center;">
                                                <button class="action-card__button editar-nucleo btn-add" name="openModal" data-modal="modalActualizarNucleo" data-id="<?= e($n['id_nucleo']) ?>" data-nombre="<?= e($n['nombre_nucleo']) ?>">
                                                    <svg style="pointer-events:none" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000000"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000000"/>
                                                    </svg>
                                                </button>
                                                <button class="btn-eliminar" name="eliminarNucleo" type="submit" nameclass="action-card__button" data-id="<?=e($n['id_nucleo'])?>">
                                                    <svg style="pointer-events:none" width="30" height="30" viewBox="0 0 512 512" fill="#000000" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M316.325 44.522V0H195.68l-.003 44.522H61.217v87.982h21.616c3.975 75.409 20.548 350.983 21.319 363.803L105.097 512h301.806l.944-15.694c.771-12.821 17.345-288.394 21.319-363.803h21.616V44.522H316.325zM229.069 33.391h53.866v11.13h-53.866V33.391zM375.458 478.609H136.542c-3.633-60.548-16.681-278.597-20.27-346.105h279.456c-3.588 67.503-16.637 285.551-20.27 346.105zM417.391 99.112H94.609V77.913h322.783v21.199z"/>
                                                        <path d="M239.304 167.947h33.391v280.031h-33.391z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="td-tabla-vacia">No hay núcleos registrados en el sistema.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>                
        </div>
        </div>
        <div id="sub-content-pnf" class="sub-tab-content subcontent-general-box configuracion-bloque-formulario" style="display: <?= $tieneGestionarOferta ? 'none' : 'block' ?>;">
            <div id="contenedor-tabla-dinamica">
                    <div class="contenedor-tabla-consultas" style="width: 100%;">
                        <div class="cabecera-tabla-global" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 class="titulo-tabla-consultas" style="margin: 0;">Programas de Formación Nacional (PNF)</h3>
                            <a name="openModal" data-modal="modalRegistrarPNF" class="action-card__button" href="#" style="width: fit-content; display: inline-block; cursor: pointer">Registrar PNF</a>
                        </div>
                        
                        <div id="alert-container-pnf" style="margin-bottom: 1rem;"></div>
                        
                        <table id="tablaPnfs" class="tabla-consultas" style="width: 100%;">
                            <thead>
                                <tr class="tr-head-consultas">
                                    <th class="th-consultas" style="text-align: left;">ID</th>
                                    <th class="th-consultas" style="text-align: left;">Nombre del PNF</th>
                                    <th class="th-consultas" style="text-align: left;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-pnf-body">
                                <?php 
                                if (!empty($pnfs)): 
                                    $i = 1; 
                                    foreach ($pnfs as $p): 
                                ?>
                                    <tr class="tr-body-consultas">
                                        <td class="td-consultas-nowrap" style="text-align: left;"><?= $i++ ?></td>
                                        <td class="td-consultas" style="text-align: left;"><strong><?= e($p['nombre_pnf']) ?></strong></td>
                                        <td class="td-acciones-btn" style="text-align: left;">
                                            
                                            <button class="action-card__button btn-add"  name="openModal" data-modal="modalActualizarPNF" data-id="<?= e($p['id_pnf']) ?>" data-nombre="<?= e($p['nombre_pnf']) ?>">
                                                    <svg style="pointer-events:none" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000000"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000000"/>
                                                </svg>
                                                </button>
                                                <button class="btn-eliminar" name="eliminarPnf" type="submit" data-id="<?= e($p['id_pnf']) ?>" class="action-card__button">
                                                    <svg style="pointer-events:none" width="30" height="30" viewBox="0 0 512 512" fill="#000000" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M316.325 44.522V0H195.68l-.003 44.522H61.217v87.982h21.616c3.975 75.409 20.548 350.983 21.319 363.803L105.097 512h301.806l.944-15.694c.771-12.821 17.345-288.394 21.319-363.803h21.616V44.522H316.325zM229.069 33.391h53.866v11.13h-53.866V33.391zM375.458 478.609H136.542c-3.633-60.548-16.681-278.597-20.27-346.105h279.456c-3.588 67.503-16.637 285.551-20.27 346.105zM417.391 99.112H94.609V77.913h322.783v21.199z"/>
                                                        <path d="M239.304 167.947h33.391v280.031h-33.391z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="td-tabla-vacia">No hay PNFs registrados en el sistema.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
       
        
</div>
    </section>

    <?php include_once __DIR__."/modals/modalEditarRol.php"; ?>
    <?php include_once __DIR__."/modals/modalEditarCondicion.php"; ?>
    <?php include_once __DIR__."/modals/modalRegistrarCondicion.php"; ?>
    <?php include_once __DIR__."/modals/modalRegistrarOferta.php"; ?>
    <?php include_once __DIR__."/modals/modalRegistrarNucleo.php"; ?>
    <?php include_once __DIR__."/modals/modalActualizarNucleo.php"; ?>
    <?php include_once __DIR__."/modals/modalRegistrarPNF.php"; ?>
    <?php include_once __DIR__."/modals/modalActualizarPNF.php"; ?>


     
    <script src="assets/script/gestionOferta.js" defer></script>
    <script src="assets/script/gestion.js" defer></script>
    <script src="assets/script/gestionpnfnucleo.js" defer></script>
</main>

