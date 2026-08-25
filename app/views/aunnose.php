<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="assets/media/img/uptaeb.jpg" type="image/x-icon">
        <link rel="preload" href="assets/css/style.css" as="style">
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/script/code.js" defer=""></script>

        <title>Consultorio</title>
    </head>
    <body style="opacity: 1;">
        <header>
            <div class="top-menu">
                <div class="top-menu__perfil">
                    <img class="top-menu__perfil-img" src="assets/media/img/uptaeb.jpg" alt="">
                    <p class="top-menu__perfil-name">Bienvenido!</p>
                </div>
                <div class="top-menu__links">
                                                    <a href="perfil" class="top-menu__link">Perfil</a>
                                </div>
                <div class="top-menu__login">
                                    <svg id="avatar" width="30px" height="30px" class="avatar" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="m 8 1 c -1.65625 0 -3 1.34375 -3 3 s 1.34375 3 3 3 s 3 -1.34375 3 -3 s -1.34375 -3 -3 -3 z m -1.5 7 c -2.492188 0 -4.5 2.007812 -4.5 4.5 v 0.5 c 0 1.109375 0.890625 2 2 2 h 8 c 1.109375 0 2 -0.890625 2 -2 v -0.5 c 0 -2.492188 -2.007812 -4.5 -4.5 -4.5 z m 0 0" fill="#2e3436"></path>
                    </svg>
                    <div id="avatarMenu" class="avatar__menu">
                        <a class="avatar__link" href="logout">Cerrar sesion</a>
                        <a class="avatar__link" href="logout">Configuración?</a>
                    </div>
                                </div>
            </div>
        </header><main class="perfil">    
            <aside class="side-menu">
            <h1>Menu</h1>
                <hr>
                <a href="perfil" id="inicio" class="focus">Inicio</a>
                            <a href="usuarios" id="usuario" class="">Usuarios</a>
                                        <a href="consultas" id="consulta" class="">Consultas</a>
                                        <a href="configuracion" id="configuracion" class="">Configuración</a>
                                    </aside>
            <section class="section-1 section-1--perfil">
                            
                <div class="buscador-caja">
                                                    
                    <div class="section-1__box transition" id="section-1-box">
                                        </div>
                </div>
                
                

                
                
                                                    <div class="dashboard-container">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h3 class="titulo-configuracion-interna" style="margin: 0;">Panel de Inicio</h3>
                                                                <a name="openModal" data-modal="modalReporteMorbilidad" class="action-card__button btn-generar-reporte" href="#" style="background-color: #0284c7; width: fit-content; text-align: center;">Generar Reporte de Morbilidad</a>
                                                        </div>
                            
                            <div class="dashboard-stats-grid">
                                <div class="stat-card">
                                    <div class="stat-card__number">0</div>
                                    <div class="stat-card__label">Consultas Realizadas</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-card__number">17</div>
                                    <div class="stat-card__label">Usuarios Registrados</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-card__number">0</div>
                                    <div class="stat-card__label">Condiciones Médicas</div>
                                </div>
                            </div>

                            <div class="contenedor-tabla-consultas">
                                <h3 class="titulo-tabla-consultas" style="text-align: left; margin-bottom: 15px;">Últimas Consultas Registradas</h3>
                                                                <div class="contenedor-historial-vacio">
                                        <p class="texto-historial-vacio">No hay consultas médicas registradas recientemente.</p>
                                    </div>
                                                        </div>
                        </div>
                                
                                
                                
            </section>
                    <dialog id="modalRegistrarUsuario" class="modal-crud">
                <div class="action-card">
        <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
        <h3 class="action-card__title">Registro</h3>
        
        <form class="action-card__form--registrar-usuarios" action="" method="POST">
        <div class="action-card__form--grid">
        <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
            <input type="hidden" name="form" value="registrar_usuario">
            
                <label for="cedula" class="action-card__label">Cédula de Identidad
                    <input type="tel" inputmode="numeric" class="action-card__input" id="cedula" name="cedula" value="" required="">
                </label>

                <label for="tipo" class="action-card__label">Tipo de Usuario
                    <select class="action-card__select" id="tipo" name="tipo" required="">
                                            <option value="" selected="" disabled="">Seleccione...</option>
                                                <option value="1">Estudiante</option>
                                                <option value="2">Docente</option>
                                                <option value="3">Administrativo</option>
                                                <option value="4">Obrero</option>
                                                <option value="6">Personal Médico</option>
                                        </select>
                </label>
            <label for="nombre" class="action-card__label">Nombres
                <input type="text" class="action-card__input" id="nombre" name="nombre" value="" required="">
            </label>
            <label for="apellido" class="action-card__label">Apellidos
                <input type="text" class="action-card__input" id="apellido" name="apellido" value="" required="">
            </label>
            
            <label for="fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                <input type="date" class="action-card__input" id="fecha_nacimiento" name="fecha_nacimiento" value="" required="">
            </label>
            <label for="tlfprincipal" class="action-card__label">Teléfono Principal
                <input type="text" class="action-card__input" id="tlfprincipal" name="tlfprincipal" value="" required="">
            </label>
            <label for="tlfemergencia" class="action-card__label">Teléfono de Emergencia
                <input type="text" class="action-card__input" id="tlfemergencia" name="tlfemergencia" value="" required="">
            </label>
            <label for="nombre_contacto_emergencia" class="action-card__label">Nombre del Contacto de Emergencia
                <input type="text" class="action-card__input" id="nombre_contacto_emergencia" name="nombre_contacto_emergencia" value="" required="">
            </label>
            
            <label for="direccion" class="action-card__label">Dirección
                <input type="text" class="action-card__input" id="direccion" name="direccion" value="" required="">
            </label>

            
            <label for="nucleo_id" class="action-card__label">Núcleo
                <select class="action-card__select" id="nucleo_id" name="nucleo_id">
                    <option value="" selected="">No aplica / Seleccione...</option>
                                </select>
            </label>

            <label for="pnf_id" class="action-card__label">PNF (Carrera)
                <select class="action-card__select" id="pnf_id" name="pnf_id" disabled="">
                    <option value="" selected="">No aplica / Seleccione...</option>
                </select>
            </label>

        </div>
            
            <label class="action-card__label">Sexo
                            
                <label>Masculino
                    <input type="radio" name="sexo" id="sexo_m" value="1" required="">
                </label>
            
                <label>Femenino
                    <input type="radio" name="sexo" id="sexo_f" value="2">
                </label>
            </label>
            <div class="action-card__button-grid">
            <button type="reset" class="action-card__button action-card__button--red">Limpiar Formulario</button>
            <button type="submit" class="action-card__button">Guardar en Sistema</button>
            </div>
        </form>
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
    </svg>        </dialog>
            <dialog id="modalActualizarUsuario" class="modal-crud">
                <div class="action-card">
        <img class="action-card__logo" src="assets/media/img/uptaeb.jpg" alt="">
        <h3 class="action-card__title">Actualizar Datos</h3>
        <p class="action-card__p">Modificación de Usuario</p>
        
        <form id="formActualizarUsuario" class="action-card__form--registrar-usuarios">
            <div class="action-card__form--grid">
                <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
                <input type="hidden" name="form" value="actualizar_usuario">
                
                <label for="edit_cedula" class="action-card__label">Cédula de Identidad
                    <input type="tel" class="action-card__input" id="edit_cedula" name="cedula" readonly="" required="">
                </label>

                <label for="edit_nombre" class="action-card__label">Nombre
                    <input type="text" class="action-card__input" id="edit_nombre" name="nombre" required="">
                </label>

                <label for="edit_apellido" class="action-card__label">Apellido
                    <input type="text" class="action-card__input" id="edit_apellido" name="apellido" required="">
                </label>

                <label for="edit_tipo" class="action-card__label">Tipo de Usuario
                    <select class="action-card__select" id="edit_tipo" name="tipo" required="">
                        <option value="" disabled="">Seleccione...</option>
                                                <option value="1">Estudiante</option>
                                                <option value="2">Docente</option>
                                                <option value="3">Administrativo</option>
                                                <option value="4">Obrero</option>
                                                <option value="6">Personal Médico</option>
                                        </select>
                </label>

                <label for="edit_fecha_nacimiento" class="action-card__label">Fecha de Nacimiento
                    <input type="date" class="action-card__input" id="edit_fecha_nacimiento" name="fecha_nacimiento" required="">
                </label>

                <label for="edit_tlfprincipal" class="action-card__label">Teléfono Principal
                    <input type="tel" class="action-card__input" id="edit_tlfprincipal" name="tlfprincipal" required="">
                </label>

                <label for="edit_nombre_contacto_emergencia" class="action-card__label">Nombre del Contacto de Emergencia
                    <input type="text" class="action-card__input" id="edit_nombre_contacto_emergencia" name="nombre_contacto_emergencia" required="">
                </label>

                <label for="edit_tlfemergencia" class="action-card__label">Teléfono de Emergencia
                    <input type="tel" class="action-card__input" id="edit_tlfemergencia" name="tlfemergencia" required="">
                </label>
                
                <label for="edit_direccion" class="action-card__label">Dirección
                    <input type="text" class="action-card__input" id="edit_direccion" name="direccion" required="">
                </label>

                
                <label for="edit_nucleo" class="action-card__label">Núcleo
                    <select class="action-card__select" id="edit_nucleo" name="nucleo_id">
                        <option value="">No aplica / Seleccione...</option>
                                        </select>
                </label>

                <label for="edit_pnf" class="action-card__label">PNF (Carrera)
                    <select class="action-card__select" id="edit_pnf" name="pnf_id" disabled="">
                        <option value="">No aplica / Seleccione...</option>
                    </select>
                </label>

                <label class="action-card__label">Sexo
                    <label for="edit_sexo_m">Masculino
                        <input type="radio" name="sexo" id="edit_sexo_m" value="1" required="">
                    </label>
                
                    <label for="edit_sexo_f">Femenino
                        <input type="radio" name="sexo" id="edit_sexo_f" value="2">
                    </label>
                </label>
            </div>

            <div class="action-card__button-grid" style="margin-top: 15px;">
                <button type="submit" class="action-card__button">Guardar Cambios</button>
            </div>
        </form>
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
    </svg>        </dialog>
            <dialog id="modalDetallesUsuario" class="modal-crud">
                <div class="action-card" id="detallesUsuario">
        
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalDetallesUsuario" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
    </svg>        </dialog>
                            <dialog id="modalRegistrarConsulta" class="modal-crud">
                <div class="action-card">
                    <h3 class="action-card__title">Registrar Consulta Médica</h3>
                    <form class="action-card__form--registrar-usuarios" action="" method="POST">
                        <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
                        <input type="hidden" name="form" value="registro_consulta">

                        <div class="action-card__form--grid">    
                            <label class="action-card__label">Paciente (Buscar por Cédula o Nombre)
                                <input type="text" id="paciente-search" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off" required="">
                                <input type="hidden" id="cedula_paciente" name="cedula_paciente">
                                <div id="pacientes-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                            </label>

                            <label class="action-card__label">Condiciones Crónicas / Permanentes del Paciente
                                <input type="text" id="condicion-search" class="action-card__input" placeholder="Buscar y añadir condición crónica (ej. Asma, Diabetes)..." autocomplete="off" disabled="">
                                <div id="condiciones-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                                <ul id="condiciones-seleccionadas" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>

                            <label for="motivo_de_visita" class="action-card__label">Motivo de la Visita
                                <textarea class="action-card__input" id="motivo_de_visita" name="motivo_de_visita" required="" disabled=""></textarea>
                            </label>

                            <label for="observaciones" class="action-card__label">Observaciones
                                <textarea class="action-card__input" id="observaciones" name="observaciones" disabled=""></textarea>
                            </label>

                            <label for="medicamento_suministrado" class="action-card__label">Medicamento Suministrado (Opcional)
                                <input type="text" class="action-card__input" id="medicamento_suministrado" name="medicamento_suministrado" placeholder="Ej. Paracetamol 500mg" disabled="">
                            </label>

                            <label class="action-card__label">Añadir Síntomas
                                <div class="sintomas-input-group" style="display: flex; gap: 8px;">
                                    <input type="text" id="sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos" disabled="" spellcheck="false">
                                    <button type="button" id="btn-add-sintoma" class="action-card__button" style="width: auto;" disabled="">Añadir</button>
                                </div>
                                <ul id="sintomas-lista" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>

                            <label class="action-card__label">Diagnósticos de la Visita (Agudos / Temporales - ICD-10)
                                <input type="text" id="diagnostico-search" class="action-card__input" placeholder="Escriba código o nombre de la patología..." autocomplete="off" disabled="">
                                <div id="diagnosticos-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                                <ul id="diagnosticos-seleccionados" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>
                        </div>

                        <div class="action-card__button-grid" style="margin-top: 20px;">
                            <button type="reset" class="action-card__button action-card__button--red">Limpiar Formulario</button>
                            <button type="submit" id="btn-registrar-consulta-submit" class="action-card__button" disabled="">Guardar en Sistema</button>
                        </div>
                    </form>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
                </svg>        </dialog>
            <dialog id="modalActualizarConsulta" class="modal-crud">
                <div class="action-card">
                    <h3 class="action-card__title">Actualizar Consulta Médica</h3>
                    
                    <div id="seccion-busqueda-paciente-actualizar">
                        <div class="action-card__form--grid">
                            <label class="action-card__label">Buscar Paciente
                                <input type="text" id="paciente-search-actualizar" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off">
                                <input type="hidden" id="cedula_paciente_actualizar">
                                <div id="pacientes-sugerencias-actualizar" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Contenedor para mostrar condiciones crónicas actuales del paciente al editar -->
                    <div id="paciente-condiciones-info-actualizar" style="margin-top: 10px; display: none; padding: 10px; background-color: #f9f9f9; border: 1px solid #eee; border-radius: 4px;"></div>

                    <div id="consultas-lista-actualizar" style="margin-top: 15px;"></div>

                    <form id="formulario-edicion-consulta" class="action-card__form--registrar-usuarios" action="index.php" method="POST" style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                        <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
                        <input type="hidden" name="form" value="actualizar_consulta">
                        <input type="hidden" id="edit_id_consulta" name="id_consulta">

                        <h4 style="margin-bottom: 15px;">Editar Detalles de la Consulta</h4>

                        <div class="action-card__form--grid">
                            <label for="edit_motivo_de_visita" class="action-card__label">Motivo de la Visita
                                <textarea class="action-card__input" id="edit_motivo_de_visita" name="motivo_de_visita" required=""></textarea>
                            </label>

                            <label for="edit_observaciones" class="action-card__label">Observaciones
                                <textarea class="action-card__input" id="edit_observaciones" name="observaciones"></textarea>
                            </label>

                            <label for="edit_medicamento_suministrado" class="action-card__label">Medicamento Suministrado (Opcional)
                                <input type="text" class="action-card__input" id="edit_medicamento_suministrado" name="medicamento_suministrado" placeholder="Ej. Paracetamol 500mg">
                            </label>

                            <label class="action-card__label">Síntomas
                                <div class="sintomas-input-group" style="display: flex; gap: 8px;">
                                    <input type="text" id="edit-sintoma-input" class="action-card__input" placeholder="Ej. Fiebre, Tos" spellcheck="false">
                                    <button type="button" id="btn-edit-add-sintoma" class="action-card__button" style="width: auto;">Añadir</button>
                                </div>
                                <ul id="edit-sintomas-lista" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>

                            <label class="action-card__label">Diagnósticos de la Visita (Agudos / Temporales - ICD-10)
                                <input type="text" id="edit-diagnostico-search" class="action-card__input" placeholder="Buscar patología..." autocomplete="off">
                                <div id="edit-diagnosticos-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                                <ul id="edit-diagnosticos-seleccionados" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>

                            <label class="action-card__label">Condiciones Crónicas / Permanentes del Paciente
                                <input type="text" id="edit-condicion-search" class="action-card__input" placeholder="Buscar y añadir condición crónica..." autocomplete="off">
                                <div id="edit-condiciones-sugerencias" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                                <ul id="edit-condiciones-seleccionadas" style="list-style-type: none; padding-left: 0; margin-top: 8px;"></ul>
                            </label>
                        </div>

                        <div class="action-card__button-grid" style="margin-top: 15px;">
                            <button type="submit" class="action-card__button">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalActualizarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
                </svg>        </dialog>
            <dialog id="modalBuscarConsulta" class="modal-crud">
                <div class="action-card">
                    <h3 class="action-card__title">Historial de Consultas Médicas</h3>
                    <div class="action-card__form--grid">
                        <label class="action-card__label">Buscar Paciente
                            <input type="text" id="paciente-search-buscar" class="action-card__input" placeholder="Escriba cédula o nombre del paciente..." autocomplete="off">
                            <input type="hidden" id="cedula_paciente_buscar">
                            <div id="pacientes-sugerencias-buscar" class="sugerencias-box" style="border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none;"></div>
                        </label>
                    </div>

                    <!-- Contenedor para mostrar condiciones crónicas actuales del paciente en historial -->
                    <div id="paciente-condiciones-info-buscar" style="margin-top: 10px; display: none; padding: 10px; background-color: #f9f9f9; border: 1px solid #eee; border-radius: 4px;"></div>

                    <div id="consultas-lista-buscar" style="margin-top: 15px; max-height: 350px; overflow-y: auto;"></div>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalBuscarConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                    <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
                </svg>        </dialog>
                            <dialog id="modalReporteMorbilidad" class="modal-crud" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
                <div class="action-card">
        <h3 class="action-card__title">Generar Reporte de Morbilidad</h3>
        <form class="action-card__form--registrar-usuarios" action="index.php" method="POST" target="_blank" id="formReporteMorbilidad">
            <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
            <input type="hidden" name="form" value="generar_reporte_morbilidad">

            <div class="action-card__form--grid">
                <label class="action-card__label" for="reporte_fecha_inicio">Fecha de Inicio
                    <input type="date" id="reporte_fecha_inicio" name="fecha_inicio" class="action-card__input" required="">
                </label>

                <label class="action-card__label" for="reporte_fecha_fin">Fecha de Fin
                    <input type="date" id="reporte_fecha_fin" name="fecha_fin" class="action-card__input" required="">
                </label>
            </div>

            <div class="action-card__button-grid" style="margin-top: 20px;">
                <button type="button" class="action-card__button action-card__button--red" onclick="const m = document.getElementById('modalReporteMorbilidad'); m.style.opacity = 0; setTimeout(() =&gt; m.close(), 150);">Cancelar</button>
                <button type="submit" class="action-card__button" style="background-color: #0284c7;">Generar Reporte (PDF)</button>
            </div>
        </form>
    </div>
    <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalReporteMorbilidad" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
        <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>
    </svg>
    <script>
    document.getElementById('formReporteMorbilidad').addEventListener('submit', function(e) {
        const inicio = document.getElementById('reporte_fecha_inicio').value;
        const fin = document.getElementById('reporte_fecha_fin').value;
        if (inicio && fin && new Date(inicio) > new Date(fin)) {
            e.preventDefault();
            alert('La fecha de inicio no puede ser posterior a la fecha de fin.');
        }
    });
    </script>
            </dialog>
                                    <dialog id="modalRegistrarCondicion" class="modal-crud">
                <div class="action-card">
                    <h3 class="action-card__title">Registrar Condición</h3>
                    <form action="index.php" method="POST" class="form-configuracion-flex">
                        <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
                        <input type="hidden" name="form" value="registrar_condicion">
                        
                        <label class="action-card__label col-nombre-rol">
                            <span class="texto-label-negrita">Nombre de la Condición</span>
                            <input type="text" name="nombre_condicion" required="" class="action-card__input">
                        </label>
                        
                        <label class="action-card__label col-nombre-rol">
                            <span class="texto-label-negrita">Descripción</span>
                            <input type="text" name="descripcion_condicion" class="action-card__input">
                        </label>
                        
                        <div class="action-card__button-grid class-margin-top-10">
                            <button type="submit" class="action-card__button">Registrar Condición</button>
                        </div>
                    </form>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalRegistrarCondicion" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>

                </svg>
            </dialog>

            <dialog id="modalEditarCondicion" class="modal-crud">
                <div class="action-card">
                    <h3 class="action-card__title">Editar Condición</h3>
                    <form action="index.php" method="POST" class="form-configuracion-flex">
                        <input type="hidden" name="csrf_token" value="61b5de984ea845e33acd29057bcda97fa8d4681f9443c9b4d346b1d7cf45aaed">
                        <input type="hidden" name="form" value="actualizar_condicion">
                        <input type="hidden" name="id" id="edit_id_condicion">
                        
                        <label class="action-card__label col-nombre-rol">
                            <span class="texto-label-negrita">Nombre de la Condición</span>
                            <input type="text" name="nombre_condicion" id="edit_nombre_condicion" required="" class="action-card__input">
                        </label>
                        
                        <label class="action-card__label col-nombre-rol">
                            <span class="texto-label-negrita">Descripción</span>
                            <input type="text" name="descripcion_condicion" id="edit_descripcion_condicion" class="action-card__input">
                        </label>
                        
                        <div class="action-card__button-grid class-margin-top-10">
                            <button type="submit" class="action-card__button">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalEditarCondicion" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>

                </svg>
            </dialog>
                    <script>
                const ES_MEDICO_O_DIRECTOR = true;
            </script>

            <dialog id="modalVerDetallesConsulta" class="modal-crud">
                <div class="action-card modal-detalles-ancho">
                    <h3 class="action-card__title">Detalles de la Consulta Médica</h3>
                    
                    <div class="modal-detalles-contenedor-datos">
                        <div><strong>Fecha:</strong> <span id="det_fecha"></span></div>
                        <div><strong>Paciente:</strong> <span id="det_paciente"></span></div>
                        <div><strong>Médico Tratante:</strong> <span id="det_medico"></span></div>
                        <div><strong>Motivo de Visita:</strong> <span id="det_motivo"></span></div>
                        <div><strong>Observaciones:</strong> <span id="det_observaciones"></span></div>
                        <div><strong>Medicamento Suministrado:</strong> <span id="det_medicamento"></span></div>
                        <div><strong>Síntomas:</strong> <span id="det_sintomas"></span></div>
                        <div><strong>Diagnósticos (CIE-10):</strong> <div id="det_diagnosticos" class="modal-detalles-diagnosticos-box"></div></div>
                    </div>
                    
                    <div class="contenedor-btn-der class-margin-top-20">
                        <button type="button" class="action-card__button btn-crear-rol" onclick="const m = document.getElementById('modalVerDetallesConsulta'); m.style.opacity = 0; setTimeout(() =&gt; m.close(), 500);">Cerrar</button>
                    </div>
                </div>
                <svg class="modal-crud__boton-cerrar" name="modalBotonCerrar" data-modal="modalVerDetallesConsulta" fill="#000000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve" style="cursor: pointer;">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z"></path>


                </svg>
            </dialog>
        
        </main>
        <footer>
            <script src="assets/script/append.js" defer=""></script>
            <script src="assets/script/eliminar.js" defer=""></script>
            <script src="assets/script/gestion.js" defer=""></script>
            <script src="assets/script/gestionpnfnucleo.js" defer=""></script>
            <script src="assets/script/gestionoferta.js" defer=""></script>
        </footer>

    </body></html>