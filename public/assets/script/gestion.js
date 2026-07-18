const inputBuscar = document.getElementById('inputBuscarUsuario');
const cuerpoTabla = document.getElementById('cuerpoTablaUsuarios');
const modalActualizar = document.getElementById('modalActualizarUsuario');
const modalDetallesUsuario = document.getElementById('modalDetallesUsuario');

function calcularEdadJS(fechaNacimiento) {
    if (!fechaNacimiento || fechaNacimiento.trim() === "") return "No registrado";
    
    const nacimiento = new Date(fechaNacimiento);
    const actual = new Date();
    
    if (nacimiento > actual) return "Fecha invalida";
    
    let edad = actual.getFullYear() - nacimiento.getFullYear();
    const mes = actual.getMonth() - nacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && actual.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}

if (inputBuscar && cuerpoTabla) {
    inputBuscar.addEventListener('input', function() {
        const textoBusqueda = inputBuscar.value.trim();
        const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `csrf_token=${tokenCSRF}&form=buscar_usuario&query=${encodeURIComponent(textoBusqueda)}`
        })
        .then(response => response.json())
        .then(usuarios => {
            cuerpoTabla.innerHTML = '';

            if (usuarios.length === 0) {
                cuerpoTabla.innerHTML = `<tr><td colspan="9" style="text-align:center;">No se encontraron usuarios.</td></tr>`;
                return;
            }

            usuarios.forEach(usuario => {
                const fila = document.createElement('tr');

                const tdActualizar = document.createElement("td");
                tdActualizar.className = "tabla-usuarios__desc";

                const btnActualizar = document.createElement("button");
                btnActualizar.className = "editar-usuario action-card__button";
                btnActualizar.innerHTML = `
                <svg style="pointer-events:none" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000000"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000000"/>
                </svg>`
                btnActualizar.dataset.id = usuario.cedula; 

                tdActualizar.appendChild(btnActualizar);

                const tdDetalles = document.createElement("td");
                tdDetalles.className = "tabla-usuarios__desc";

                const btnDetalles = document.createElement("button");
                btnDetalles.className = "consultar-usuario action-card__button";
                btnDetalles.innerHTML = `
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="13" r="2" stroke="#000000" stroke-linejoin="round"/>
                    <path d="M12 7.5C7.69517 7.5 4.47617 11.0833 3.39473 12.4653C3.14595 12.7832 3.14595 13.2168 3.39473 13.5347C4.47617 14.9167 7.69517 18.5 12 18.5C16.3048 18.5 19.5238 14.9167 20.6053 13.5347C20.8541 13.2168 20.8541 12.7832 20.6053 12.4653C19.5238 11.0833 16.3048 7.5 12 7.5Z" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                `
                btnDetalles.dataset.id = usuario.cedula;

                tdDetalles.appendChild(btnDetalles);

                const tdEliminar = document.createElement("td");
                tdEliminar.className = "tabla-usuarios__desc";

                const btnEliminar = document.createElement("button");
                btnEliminar.className = "eliminar-usuario action-card__button";
                btnEliminar.innerHTML = `
                <svg width="30" height="30" viewBox="0 0 512 512" fill="#000000" xmlns="http://www.w3.org/2000/svg">
                    <path d="M316.325 44.522V0H195.68l-.003 44.522H61.217v87.982h21.616c3.975 75.409 20.548 350.983 21.319 363.803L105.097 512h301.806l.944-15.694c.771-12.821 17.345-288.394 21.319-363.803h21.616V44.522H316.325zM229.069 33.391h53.866v11.13h-53.866V33.391zM375.458 478.609H136.542c-3.633-60.548-16.681-278.597-20.27-346.105h279.456c-3.588 67.503-16.637 285.551-20.27 346.105zM417.391 99.112H94.609V77.913h322.783v21.199z"/>
                    <path d="M239.304 167.947h33.391v280.031h-33.391z"/>
                    <path d="M160.292 168.19h33.39v279.952h-33.39z" transform="matrix(.9986 -.0521 .0521 .9986 -15.8157 9.64)"/>
                    <path d="M195.052 291.462h279.952v33.39H195.052z" transform="matrix(.0521 -.9986 .9986 .0521 9.8344 626.6741)"/>
                </svg>
                `;
                btnEliminar.dataset.id = usuario.cedula;

                tdEliminar.appendChild(btnEliminar);
            
                function crearTd(valor) {
                    const td = document.createElement("td");
                    td.classList.add("tabla-usuarios__desc")
                    td.textContent = valor
                    return td;
                }
                
                fila.append(
                    crearTd(usuario.cedula),
                    crearTd(usuario.nombre),
                    crearTd(usuario.apellido),
                    crearTd(usuario.nombre_tipo || usuario.tipo),
                    crearTd(calcularEdadJS(usuario.fecha_nacimiento)),
                    crearTd(usuario.tlfprincipal),
                    tdDetalles,
                    tdActualizar, 
                    tdEliminar
                );
                cuerpoTabla.appendChild(fila);
            });
        })
        .catch(error => console.error("Error al buscar:", error));
    });
}

if (cuerpoTabla && modalActualizar) {
    cuerpoTabla.addEventListener('click', function(event) {
        if (event.target.classList.contains('editar-usuario')) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_usuario&id=${cedula}`
            })
            .then(response => response.json())
            .then(usuario => {
                if (usuario.error) {
                    alert(usuario.error);
                    return;
                }

                document.getElementById('edit_cedula').value = usuario.cedula;
                document.getElementById('edit_nombre').value = usuario.nombre || '';
                document.getElementById('edit_apellido').value = usuario.apellido || '';
                document.getElementById('edit_tipo').value = usuario.tipo;
                document.getElementById('edit_fecha_nacimiento').value = usuario.fecha_nacimiento || '';
                document.getElementById('edit_tlfprincipal').value = usuario.tlfprincipal || '';
                document.getElementById('edit_nombre_contacto_emergencia').value = usuario.nombre_contacto_emergencia || '';
                document.getElementById('edit_tlfemergencia').value = usuario.tlfemergencia || '';
                document.getElementById('edit_direccion').value = usuario.direccion || '';

                const editRolField = document.getElementById('edit_rol');
                if (editRolField && usuario.rol !== undefined) {
                    editRolField.value = usuario.rol;
                }

                const editNucleoField = document.getElementById('edit_nucleo');
                const editPnfField = document.getElementById('edit_pnf');
                if (editNucleoField && editPnfField) {
                    editNucleoField.value = usuario.nucleo_id || '';
                    cargarPnfsPorNucleo(usuario.nucleo_id, editPnfField, usuario.pnf_id);
                }

                if (usuario.sexo == 1) {
                    document.getElementById('edit_sexo_m').checked = true;
                } else if (usuario.sexo == 2) {
                    document.getElementById('edit_sexo_f').checked = true;
                }

                modalActualizar.showModal();

                setTimeout(() => {
                    modalActualizar.style.opacity = "1"
                }, 500);
            })
            .catch(error => console.error("Error al cargar datos del usuario:", error));
        }
    });
}

if (cuerpoTabla && modalActualizar) {
    cuerpoTabla.addEventListener('click', function(event) {
        if (event.target.classList.contains('consultar-usuario')) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_usuario&id=${cedula}`
            })
            .then(response => response.json())
            .then(usuario => {
                if (usuario.error) {
                    alert(usuario.error);
                    return;
                }
                const detalles = document.getElementById('detallesUsuario')
                detalles.innerHTML = ""

                function crearP(titulo, valor) {
                    const p = document.createElement("p");
                    p.textContent = `${titulo}${valor}`;
                    return p;
                }
                const contenido = document.createDocumentFragment()
        
                contenido.append(
                    crearP("Cédula: ", usuario.cedula),
                    crearP("Nombre: ", usuario.nombre),
                    crearP("Apellido: ", usuario.apellido),
                    crearP("Tipo: ", usuario.nombre_tipo || usuario.tipo),
                    crearP("Fecha de Nacimiento: ", usuario.fecha_nacimiento),
                    crearP("Edad: ", calcularEdadJS(usuario.fecha_nacimiento)),
                    crearP("Teléfono Principal: ", usuario.tlfprincipal),
                    crearP("Teléfono Emergencia: ", usuario.tlfemergencia),
                    crearP("Contacto de Emergencia: ", usuario.nombre_contacto_emergencia),
                    crearP("Dirección: ", usuario.direccion),
                    crearP("Sexo: ", usuario.sexo == 1 ? "Masculino" : "Femenino"),
                    crearP("Núcleo: ", usuario.nombre_nucleo || "No asignado"),
                    crearP("PNF: ", usuario.nombre_pnf || "No asignado"),
                );

                detalles.append(contenido)

                const editRolField = document.getElementById('edit_rol');
                if (editRolField && usuario.rol !== undefined) {
                    editRolField.value = usuario.rol;
                }

                if (usuario.sexo == 1) {
                    document.getElementById('edit_sexo_m').checked = true;
                } else if (usuario.sexo == 2) {
                    document.getElementById('edit_sexo_f').checked = true;
                }

                modalDetallesUsuario.showModal();

                setTimeout(() => {
                    modalDetallesUsuario.style.opacity = "1"
                }, 500);
            })
            .catch(error => console.error("Error al cargar datos del usuario:", error));
        }
    });
}

const formActualizar = document.getElementById('formActualizarUsuario');
if (formActualizar) {
    formActualizar.addEventListener('submit', function(event) {
        event.preventDefault();

        const datosEnvio = new URLSearchParams(new FormData(formActualizar)).toString();

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datosEnvio
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("¡Datos del usuario actualizados correctamente!");
                modalActualizar.close();
                
                if (inputBuscar) {
                    inputBuscar.dispatchEvent(new Event('input'));
                } else {
                    location.reload();
                }
            } else {
                alert("Error al actualizar: " + data.message);
            }
        })
        .catch(error => console.error("Error al procesar actualización:", error));
    });
}

const inputBuscarC = document.getElementById('inputBuscarConsulta');
const cuerpoTablaConsultas = document.getElementById('cuerpoTablaConsultas');
const modalActualizarConsulta = document.getElementById('modalActualizarConsulta');
const btnCargarMas = document.getElementById('btnCargarMasConsultas');

function checkCargarMasVisibility() {
    if (!btnCargarMas || !cuerpoTablaConsultas) return;
    const rowsCount = cuerpoTablaConsultas.querySelectorAll('tr:not(.no-registros)').length;
    if (rowsCount > 0 && rowsCount % 20 === 0) {
        btnCargarMas.style.display = 'block';
    } else {
        btnCargarMas.style.display = 'none';
    }
}

// Run initial visibility check on page load
checkCargarMasVisibility();

if (inputBuscarC && cuerpoTablaConsultas) {
    inputBuscarC.addEventListener('input', function() {
        const textoBusqueda = inputBuscarC.value.trim();
        const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `csrf_token=${tokenCSRF}&form=buscar_consultas&query=${encodeURIComponent(textoBusqueda)}&offset=0`
        })
        .then(response => response.json())
        .then(consultas => {
            cuerpoTablaConsultas.innerHTML = '';

            if (consultas.length === 0) {
                cuerpoTablaConsultas.innerHTML = `<tr class="no-registros"><td colspan="7" style="text-align:center; padding: 30px; color: #666;">No hay ninguna consulta asociada a ese usuario.</td></tr>`;
                if (btnCargarMas) btnCargarMas.style.display = 'none';
                return;
            }

            consultas.forEach(c => {
                const fila = document.createElement('tr');
                fila.style.borderBottom = '1px solid #eee';

                const dateObj = new Date(c.fecha_consulta);
                const formattedDate = !isNaN(dateObj) ? 
                    `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}` : 
                    c.fecha_consulta;

                const pacienteNombre = `${c.paciente_nombre || ''} ${c.paciente_apellido || ''}`.trim();
                const medicoNombre = `${c.medico_nombre || ''} ${c.medico_apellido || ''}`.trim();

                let sintomasHtml = '<span style="color: #999;">Ninguno</span>';
                if (c.sintomas && c.sintomas.length > 0) {
                    sintomasHtml = c.sintomas.join(', ');
                }

                let diagsHtml = '<span style="color: #999;">Sin diagnóstico</span>';
                if (c.diagnosticos && c.diagnosticos.length > 0) {
                    diagsHtml = '';
                    c.diagnosticos.forEach(d => {
                        diagsHtml += `<div style="margin-bottom: 2px;"><strong style="color: #b91c1c;">${d.codigo_icd_diagnostico}</strong> - ${d.patologia || 'Sin detalle'}</div>`;
                    });
                }

                fila.innerHTML = `
                    <td style="padding: 10px; font-size: 0.9em; white-space: nowrap;">${formattedDate}</td>
                    <td style="padding: 10px; font-size: 0.9em;">
                        <strong>${pacienteNombre}</strong>
                        <div style="font-size: 0.8em; color: #666;">C.I. ${c.id_usuario}</div>
                    </td>
                    <td style="padding: 10px; font-size: 0.9em;">${medicoNombre}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${c.motivo_de_visita}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${sintomasHtml}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${diagsHtml}</td>
                    <td style="padding: 10px; font-size: 0.9em; display: flex; gap: 5px;">
                        <button class="ver-detalles-consulta action-card__button" data-id="${c.id}" style="background: #4a5568; color: #fff;">Ver detalles</button>
                        ${ES_MEDICO_O_DIRECTOR ? `<button class="editar-consulta action-card__button" data-id="${c.id}">Actualizar</button>` : ''}
                    </td>
                `;
                cuerpoTablaConsultas.appendChild(fila);
            });

            checkCargarMasVisibility();
        })
        .catch(error => console.error("Error al buscar consultas:", error));
    });
}

if (btnCargarMas && cuerpoTablaConsultas) {
    btnCargarMas.addEventListener('click', function() {
        const query = inputBuscarC ? inputBuscarC.value.trim() : '';
        const offset = cuerpoTablaConsultas.querySelectorAll('tr:not(.no-registros)').length;
        const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `csrf_token=${tokenCSRF}&form=buscar_consultas&query=${encodeURIComponent(query)}&offset=${offset}`
        })
        .then(response => response.json())
        .then(consultas => {
            if (consultas.length === 0) {
                btnCargarMas.style.display = 'none';
                return;
            }

            consultas.forEach(c => {
                const fila = document.createElement('tr');
                fila.style.borderBottom = '1px solid #eee';

                const dateObj = new Date(c.fecha_consulta);
                const formattedDate = !isNaN(dateObj) ? 
                    `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}` : 
                    c.fecha_consulta;

                const pacienteNombre = `${c.paciente_nombre || ''} ${c.paciente_apellido || ''}`.trim();
                const medicoNombre = `${c.medico_nombre || ''} ${c.medico_apellido || ''}`.trim();

                let sintomasHtml = '<span style="color: #999;">Ninguno</span>';
                if (c.sintomas && c.sintomas.length > 0) {
                    sintomasHtml = c.sintomas.join(', ');
                }

                let diagsHtml = '<span style="color: #999;">Sin diagnóstico</span>';
                if (c.diagnosticos && c.diagnosticos.length > 0) {
                    diagsHtml = '';
                    c.diagnosticos.forEach(d => {
                        diagsHtml += `<div style="margin-bottom: 2px;"><strong style="color: #b91c1c;">${d.codigo_icd_diagnostico}</strong> - ${d.patologia || 'Sin detalle'}</div>`;
                    });
                }

                fila.innerHTML = `
                    <td style="padding: 10px; font-size: 0.9em; white-space: nowrap;">${formattedDate}</td>
                    <td style="padding: 10px; font-size: 0.9em;">
                        <strong>${pacienteNombre}</strong>
                        <div style="font-size: 0.8em; color: #666;">C.I. ${c.id_usuario}</div>
                    </td>
                    <td style="padding: 10px; font-size: 0.9em;">${medicoNombre}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${c.motivo_de_visita}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${sintomasHtml}</td>
                    <td style="padding: 10px; font-size: 0.9em;">${diagsHtml}</td>
                    <td style="padding: 10px; font-size: 0.9em; display: flex; gap: 5px;">
                        <button class="ver-detalles-consulta action-card__button" data-id="${c.id}" style="background: #4a5568; color: #fff;">Ver detalles</button>
                        ${ES_MEDICO_O_DIRECTOR ? `<button class="editar-consulta action-card__button" data-id="${c.id}">Actualizar</button>` : ''}
                    </td>
                `;
                cuerpoTablaConsultas.appendChild(fila);
            });

            checkCargarMasVisibility();
        })
        .catch(error => console.error("Error al cargar más consultas:", error));
    });
}

if (cuerpoTablaConsultas && modalActualizarConsulta) {
    cuerpoTablaConsultas.addEventListener('click', function(event) {
        if (event.target.classList.contains('ver-detalles-consulta')) {
            event.preventDefault();
            const idConsulta = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;
            const modalVer = document.getElementById('modalVerDetallesConsulta');

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_consulta&id=${idConsulta}`
            })
            .then(response => response.json())
            .then(consulta => {
                if (consulta.error) {
                    alert(consulta.error);
                    return;
                }

                const dateObj = new Date(consulta.fecha_consulta);
                const formattedDate = !isNaN(dateObj) ? 
                    `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}` : 
                    consulta.fecha_consulta;

                document.getElementById('det_fecha').textContent = formattedDate;
                document.getElementById('det_paciente').textContent = `${consulta.paciente_nombre || ''} ${consulta.paciente_apellido || ''} (C.I. ${consulta.id_usuario})`;
                document.getElementById('det_medico').textContent = `${consulta.medico_nombre || ''} ${consulta.medico_apellido || ''}`;
                document.getElementById('det_motivo').textContent = consulta.motivo_de_visita || 'Ninguno';
                document.getElementById('det_observaciones').textContent = consulta.observaciones || 'Ninguna';
                document.getElementById('det_medicamento').textContent = consulta.medicamento_suministrado || 'Ninguno';

                const sintomasSpan = document.getElementById('det_sintomas');
                if (consulta.sintomas && consulta.sintomas.length > 0) {
                    sintomasSpan.textContent = consulta.sintomas.join(', ');
                } else {
                    sintomasSpan.innerHTML = '<span style="color: #999;">Ninguno</span>';
                }

                const diagnosticosDiv = document.getElementById('det_diagnosticos');
                diagnosticosDiv.innerHTML = '';
                if (consulta.diagnosticos && consulta.diagnosticos.length > 0) {
                    consulta.diagnosticos.forEach(d => {
                        const div = document.createElement('div');
                        div.style.marginBottom = '4px';
                        div.innerHTML = `<strong style="color: #b91c1c;">${d.codigo_icd_diagnostico}</strong> - ${d.patologia || 'Sin detalle'}`;
                        diagnosticosDiv.appendChild(div);
                    });
                } else {
                    diagnosticosDiv.innerHTML = '<span style="color: #999;">Sin diagnóstico</span>';
                }

                modalVer.showModal();
                setTimeout(() => {
                    modalVer.style.opacity = '1';
                }, 50);
            })
            .catch(error => console.error("Error al cargar detalles de la consulta:", error));
        }

        if (event.target.classList.contains('editar-consulta')) {
            event.preventDefault();
            
            const idConsulta = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;

            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_consulta&id=${idConsulta}`
            })
            .then(response => response.json())
            .then(consulta => {
                if (consulta.error) {
                    alert(consulta.error);
                    return;
                }

                // Hide search and show only the edit form
                const editForm = document.getElementById("formulario-edicion-consulta");
                const searchSection = document.getElementById("seccion-busqueda-paciente-actualizar");
                const listContainer = document.getElementById("consultas-lista-actualizar");
                const condInfo = document.getElementById("paciente-condiciones-info-actualizar");

                if (editForm) editForm.style.display = "block";
                if (searchSection) searchSection.style.display = "none";
                if (listContainer) listContainer.innerHTML = "";
                if (condInfo) condInfo.style.display = "none";

                if (typeof loadConsultaIntoEditForm === 'function') {
                    loadConsultaIntoEditForm(consulta);
                }

                modalActualizarConsulta.showModal();
                setTimeout(() => {
                    modalActualizarConsulta.style.opacity = "1";
                }, 500);
            })
            .catch(error => console.error("Error al cargar datos de la consulta:", error));
        }
    });
}

// Handle top menu "Actualizar consulta" button click to show search section and hide form
const btnActualizarConsultaTop = document.querySelector('[data-modal="modalActualizarConsulta"]');
if (btnActualizarConsultaTop) {
    btnActualizarConsultaTop.addEventListener('click', function() {
        const editForm = document.getElementById("formulario-edicion-consulta");
        const searchSection = document.getElementById("seccion-busqueda-paciente-actualizar");
        const listContainer = document.getElementById("consultas-lista-actualizar");
        const condInfo = document.getElementById("paciente-condiciones-info-actualizar");
        const searchInput = document.getElementById("paciente-search-actualizar");
        const hiddenInput = document.getElementById("cedula_paciente_actualizar");

        if (editForm) editForm.style.display = "none";
        if (searchSection) searchSection.style.display = "block";
        if (listContainer) listContainer.innerHTML = "";
        if (condInfo) condInfo.style.display = "none";
        if (searchInput) searchInput.value = "";
        if (hiddenInput) hiddenInput.value = "";
    });
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('editar-condicion')) {
        event.preventDefault();
        
        const idCondicion = event.target.getAttribute('data-id');
        const nombreCondicion = event.target.getAttribute('data-nombre');
        const descripcionCondicion = event.target.getAttribute('data-descripcion');
        
        const modalEditar = document.getElementById('modalEditarCondicion');
        if (modalEditar) {
            document.getElementById('edit_id_condicion').value = idCondicion;
            document.getElementById('edit_nombre_condicion').value = nombreCondicion;
            document.getElementById('edit_descripcion_condicion').value = descripcionCondicion;
            
            modalEditar.showModal();
            setTimeout(() => {
                modalEditar.style.opacity = '1';
            }, 50);
        }
    }
});

const inputBuscarCondicion = document.getElementById('inputBuscarCondicion');
const cuerpoTablaCondiciones = document.getElementById('cuerpoTablaCondiciones');

if (inputBuscarCondicion && cuerpoTablaCondiciones) {
    const filas = Array.from(cuerpoTablaCondiciones.querySelectorAll('tr'));
    const esVacia = filas.length === 1 && filas[0].querySelector('.td-tabla-vacia') && !filas[0].classList.contains('fila-vacia-sugerida');
    
    if (!esVacia) {
        function filtrarCondiciones() {
            const query = inputBuscarCondicion.value.toLowerCase().trim();
            let mostrados = 0;
            
            filas.forEach(fila => {
                if (fila.classList.contains('fila-vacia-sugerida')) return;
                
                const nombre = fila.children[1]?.textContent.toLowerCase() || "";
                const descripcion = fila.children[2]?.textContent.toLowerCase() || "";
                const coincide = nombre.includes(query) || descripcion.includes(query);
                
                if (coincide && mostrados < 10) {
                    fila.style.display = "";
                    mostrados++;
                } else {
                    fila.style.display = "none";
                }
            });
            
            let rowVacio = cuerpoTablaCondiciones.querySelector('.fila-vacia-sugerida');
            if (mostrados === 0 && query !== "") {
                if (!rowVacio) {
                    rowVacio = document.createElement('tr');
                    rowVacio.className = 'fila-vacia-sugerida';
                    rowVacio.innerHTML = '<td colspan="4" class="td-tabla-vacia" style="text-align:center;">No se encontraron condiciones que coincidan.</td>';
                    cuerpoTablaCondiciones.appendChild(rowVacio);
                } else {
                    rowVacio.style.display = "";
                }
            } else if (rowVacio) {
                rowVacio.style.display = "none";
            }
        }
        
        inputBuscarCondicion.addEventListener('input', filtrarCondiciones);
        filtrarCondiciones();
    }
}

function cargarPnfsPorNucleo(idNucleo, selectPnfElement, pnfSeleccionado = null) {
    if (!selectPnfElement) return;

    selectPnfElement.innerHTML = '<option value="">No aplica / Seleccione...</option>';
    selectPnfElement.disabled = true;

    if (!idNucleo || idNucleo === "") {
        return;
    }

    const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;

    fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `csrf_token=${tokenCSRF}&form=obtener_pnfs_por_nucleo&id_nucleo=${idNucleo}`
    })
    .then(response => response.json())
    .then(pnfs => {
        if (Array.isArray(pnfs) && pnfs.length > 0) {
            selectPnfElement.disabled = false;
            pnfs.forEach(pnf => {
                const opt = document.createElement('option');
                opt.value = pnf.id_pnf;
                opt.textContent = pnf.nombre_pnf;
                if (pnfSeleccionado && String(pnf.id_pnf) === String(pnfSeleccionado)) {
                    opt.selected = true;
                }
                selectPnfElement.appendChild(opt);
            });
        }
    })
    .catch(error => console.error("Error al cargar PNFs:", error));
}

const selectNucleoReg = document.getElementById('nucleo_id');
const selectPnfReg = document.getElementById('pnf_id');
if (selectNucleoReg && selectPnfReg) {
    selectNucleoReg.addEventListener('change', function() {
        cargarPnfsPorNucleo(this.value, selectPnfReg);
    });
}

const selectNucleoEdit = document.getElementById('edit_nucleo');
const selectPnfEdit = document.getElementById('edit_pnf');
if (selectNucleoEdit && selectPnfEdit) {
    selectNucleoEdit.addEventListener('change', function() {
        cargarPnfsPorNucleo(this.value, selectPnfEdit);
    });
}