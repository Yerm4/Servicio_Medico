const inputBuscar = document.getElementById('inputBuscarUsuario');
const cuerpoTabla = document.getElementById('cuerpoTablaUsuarios');
const elModalActualizar = document.getElementById('modalActualizarUsuario');

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
                
                const tipoUsuario = usuario.nombre_tipo || (usuario.tipo == 0 ? 'Estudiante' : 'Docente');
                const sexoUsuario = usuario.sexo == 1 ? 'Masculino' : 'Femenino';
                const edadCalculada = calcularEdadJS(usuario.fecha_nacimiento);

                fila.innerHTML = `
                    <td class="tabla-usuarios__desc">${usuario.cedula}</td>
                    <td class="tabla-usuarios__desc">${usuario.nombre || ''}</td>
                    <td class="tabla-usuarios__desc">${usuario.apellido || ''}</td>
                    <td class="tabla-usuarios__desc">${tipoUsuario}</td>
                    <td class="tabla-usuarios__desc">${edadCalculada}</td>
                    <td class="tabla-usuarios__desc">${sexoUsuario}</td>
                    <td class="tabla-usuarios__desc">${usuario.tlfprincipal || ''}</td>
                    <td class="tabla-usuarios__desc">
                        <button class="editar-usuario action-card__button" data-id="${usuario.cedula}">Actualizar</button>
                    </td>
                    <td class="tabla-usuarios__desc">
                        <button class="eliminar-usuario action-card__button" data-id="${usuario.cedula}">Eliminar</button>
                    </td>
                `;
                cuerpoTabla.appendChild(fila);
            });
        })
        .catch(error => console.error("Error al buscar:", error));
    });
}

if (cuerpoTabla && elModalActualizar) {
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

                if (usuario.sexo == 1) {
                    document.getElementById('edit_sexo_m').checked = true;
                } else if (usuario.sexo == 2) {
                    document.getElementById('edit_sexo_f').checked = true;
                }

                elModalActualizar.showModal();

                setTimeout(() => {
                    elModalActualizar.style.opacity = "1"
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
                elModalActualizar.close();
                
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
const elModalActualizarConsulta = document.getElementById('modalActualizarConsulta');
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

if (cuerpoTablaConsultas && elModalActualizarConsulta) {
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

                elModalActualizarConsulta.showModal();
                setTimeout(() => {
                    elModalActualizarConsulta.style.opacity = "1";
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