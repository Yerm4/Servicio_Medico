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

const inputBuscarC = document.getElementById('inputBuscarConsulta');
const cuerpoTablaConsultas = document.getElementById('cuerpoTablaConsultas');
const modalActualizarConsulta = document.getElementById('modalActualizarConsulta');
const btnCargarMas = document.getElementById('btnCargarMasConsultas');

function checkCargarMasVisibility() {
    if (!btnCargarMas || !cuerpoTablaConsultas) return;
    const rowsCount = cuerpoTablaConsultas.querySelectorAll('tr:not(.no-registros)').length;
    btnCargarMas.style.display = (rowsCount > 0 && rowsCount % 20 === 0) ? 'block' : 'none';
}

checkCargarMasVisibility();

function renderFilaConsulta(c) {
    const fila = document.createElement('tr');
    fila.className = 'tr-body-consultas';

    const dateObj = new Date(c.fecha_consulta);
    const formattedDate = !isNaN(dateObj) ? 
        `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}` : 
        c.fecha_consulta;

    const pacienteNombre = `${c.paciente_nombre || ''} ${c.paciente_apellido || ''}`.trim();
    const medicoNombre = `${c.medico_nombre || ''} ${c.medico_apellido || ''}`.trim();

    let sintomasHtml = '<span class="sintomas-ninguno">Ninguno</span>';
    if (c.sintomas && c.sintomas.length > 0) {
        sintomasHtml = c.sintomas.join(', ');
    }

    let diagsHtml = '<span class="sintomas-ninguno">Sin diagnóstico</span>';
    if (c.diagnosticos && c.diagnosticos.length > 0) {
        diagsHtml = c.diagnosticos.map(d => 
            `<div class="diagnostico-item-tabla"><strong class="diagnostico-codigo">${d.codigo_icd_diagnostico}</strong> - ${d.patologia || 'Sin detalle'}</div>`
        ).join('');
    }

    fila.innerHTML = `
        <td class="td-consultas-nowrap">${formattedDate}</td>
        <td class="td-consultas">
            <strong>${pacienteNombre}</strong>
            <div class="td-paciente-sub">C.I. ${c.id_usuario}</div>
        </td>
        <td class="td-consultas">${medicoNombre}</td>
        <td class="td-consultas">${c.motivo_de_visita}</td>
        <td class="td-consultas">${sintomasHtml}</td>
        <td class="td-consultas">${diagsHtml}</td>
        <td class="td-acciones-btn">
            <button class="ver-detalles-consulta action-card__button btn-detalles-consulta" data-id="${c.id}">Ver detalles</button>
            ${ES_MEDICO_O_DIRECTOR ? `<button class="editar-consulta action-card__button" data-id="${c.id}">Actualizar</button>` : ''}
        </td>
    `;
    return fila;
}

if (inputBuscarC && cuerpoTablaConsultas) {
    inputBuscarC.addEventListener('input', function() {
        const textoBusqueda = inputBuscarC.value.trim();

        fetch(`api/consulta?query=${encodeURIComponent(textoBusqueda)}`)
        .then(response => response.json())
        .then(res => {
            const consultas = Array.isArray(res) ? res : (res.data || []);
            cuerpoTablaConsultas.innerHTML = '';

            if (consultas.length === 0) {
                cuerpoTablaConsultas.innerHTML = `<tr class="no-registros"><td colspan="7" class="td-tabla-vacia">No hay ninguna consulta asociada a ese usuario.</td></tr>`;
                if (btnCargarMas) btnCargarMas.style.display = 'none';
                return;
            }

            consultas.forEach(c => cuerpoTablaConsultas.appendChild(renderFilaConsulta(c)));
            checkCargarMasVisibility();
        })
        .catch(error => console.error("Error al buscar consultas:", error));
    });
}

if (btnCargarMas && cuerpoTablaConsultas) {
    btnCargarMas.addEventListener('click', function() {
        const query = inputBuscarC ? inputBuscarC.value.trim() : '';
        const offset = cuerpoTablaConsultas.querySelectorAll('tr:not(.no-registros)').length;

        fetch(`api/consulta?query=${encodeURIComponent(query)}&offset=${offset}`)
        .then(response => response.json())
        .then(res => {
            const consultas = Array.isArray(res) ? res : (res.data || []);
            if (consultas.length === 0) {
                btnCargarMas.style.display = 'none';
                return;
            }

            consultas.forEach(c => cuerpoTablaConsultas.appendChild(renderFilaConsulta(c)));
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
            const modalVer = document.getElementById('modalVerDetallesConsulta');

            fetch(`api/consulta/${idConsulta}`)
            .then(response => response.json())
            .then(res => {
                const consulta = res.data || res;
                if (consulta.error || res.status === 'error') {
                    alert(consulta.error || res.message);
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
                sintomasSpan.textContent = (consulta.sintomas && consulta.sintomas.length > 0) 
                    ? consulta.sintomas.join(', ') 
                    : 'Ninguno';

                const diagnosticosDiv = document.getElementById('det_diagnosticos');
                diagnosticosDiv.innerHTML = '';
                if (consulta.diagnosticos && consulta.diagnosticos.length > 0) {
                    consulta.diagnosticos.forEach(d => {
                        const div = document.createElement('div');
                        div.className = 'diagnostico-item-tabla';
                        div.innerHTML = `<strong class="diagnostico-codigo">${d.codigo_icd_diagnostico}</strong> - ${d.patologia || 'Sin detalle'}`;
                        diagnosticosDiv.appendChild(div);
                    });
                } else {
                    diagnosticosDiv.innerHTML = '<span class="sintomas-ninguno">Sin diagnóstico</span>';
                }

                modalVer.showModal();
                setTimeout(() => modalVer.style.opacity = '1', 50);
            })
            .catch(error => console.error("Error al cargar detalles de la consulta:", error));
        }

        if (event.target.classList.contains('editar-consulta')) {
            event.preventDefault();
            const idConsulta = event.target.getAttribute('data-id');

            fetch(`api/consulta/${idConsulta}`)
            .then(response => response.json())
            .then(res => {
                const consulta = res.data || res;
                if (consulta.error || res.status === 'error') {
                    alert(consulta.error || res.message);
                    return;
                }

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
                setTimeout(() => modalActualizarConsulta.style.opacity = "1", 500);
            })
            .catch(error => console.error("Error al cargar datos de la consulta:", error));
        }
    });
}

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
            setTimeout(() => modalEditar.style.opacity = '1', 50);
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
                    rowVacio.innerHTML = '<td colspan="4" class="td-tabla-vacia">No se encontraron condiciones que coincidan.</td>';
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

    if (!idNucleo || idNucleo === "") return;

    fetch(`api/nucleos/pnfs/${idNucleo}`)
    .then(response => response.json())
    .then(res => {
        const pnfs = Array.isArray(res) ? res : (res.data || []);
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

// -------------------------------------------------------------------------
// FUNCIONES AUXILIARES DE GESTIÓN Y ALERTAS (ROLES, CONDICIONES, CONFIG)
// -------------------------------------------------------------------------
function mostrarAlertaGestion(contenedor, mensaje, tipo = "success") {
    const target = typeof contenedor === "string" ? document.getElementById(contenedor) : contenedor;
    if (!target) return;

    const colorBorde = tipo === "success" ? "#2ecc71" : "#e74c3c";
    const alertaEl = document.createElement("div");
    alertaEl.className = "action-card";
    alertaEl.style.cssText = `padding: 1rem; border-left: 5px solid ${colorBorde}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;`;

    alertaEl.innerHTML = `
        <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <span>${mensaje}</span>
            <span class="btn-cerrar-alerta" style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;">×</span>
        </p>
    `;

    alertaEl.querySelector(".btn-cerrar-alerta")?.addEventListener("click", () => alertaEl.remove());
    target.appendChild(alertaEl);
    setTimeout(() => alertaEl.remove(), 4000);
}

async function refrescarFragmento(idElemento) {
    try {
        const response = await fetch(window.location.href);
        const html = await response.text();
        const doc = new DOMParser().parseFromString(html, "text/html");
        const nuevo = doc.getElementById(idElemento);
        const actual = document.getElementById(idElemento);
        if (nuevo && actual) {
            actual.innerHTML = nuevo.innerHTML;
        }
    } catch (err) {
        console.error(`Error al refrescar #${idElemento}:`, err);
    }
}

// -------------------------------------------------------------------------
// CRUD ROLES (AJAX)
// -------------------------------------------------------------------------
const formRegistrarRol = document.getElementById("formRegistrarRol");
const alertContainerRoles = document.getElementById("alert-container-roles");

if (formRegistrarRol) {
    formRegistrarRol.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formRegistrarRol);
        const datos = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("api/roles", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                formRegistrarRol.reset();
                await refrescarFragmento("cuerpoTablaRoles");
                await refrescarFragmento("contenedorMatrizPermisos");
                await refrescarFragmento("selectRolDefecto");
                mostrarAlertaGestion(alertContainerRoles, data.message || "¡Rol creado exitosamente!", "success");
            } else {
                mostrarAlertaGestion(alertContainerRoles, data?.message || "Error al crear el rol", "error");
            }
        } catch (err) {
            console.error("Error al registrar rol:", err);
            mostrarAlertaGestion(alertContainerRoles, "Error en la comunicación con el servidor", "error");
        }
    });
}

const formEditarRol = document.getElementById("formEditarRol");
const modalEditarRol = document.getElementById("modalEditarRol");

if (formEditarRol) {
    formEditarRol.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formEditarRol);
        const datos = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("api/roles", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                modalEditarRol?.close?.();
                if (modalEditarRol) modalEditarRol.style.opacity = "0";
                await refrescarFragmento("cuerpoTablaRoles");
                await refrescarFragmento("contenedorMatrizPermisos");
                await refrescarFragmento("selectRolDefecto");
                mostrarAlertaGestion(alertContainerRoles, data.message || "¡Rol actualizado con éxito!", "success");
            } else {
                alert(data?.message || "Error al actualizar el rol");
            }
        } catch (err) {
            console.error("Error al actualizar rol:", err);
            alert("Error en la comunicación con el servidor");
        }
    });
}

document.addEventListener("submit", async (e) => {
    const formEliminarRol = e.target.closest(".form-eliminar-rol");
    if (!formEliminarRol) return;

    e.preventDefault();
    if (!confirm("¿Seguro que deseas eliminar este rol?")) return;

    const formData = new FormData(formEliminarRol);
    const datos = Object.fromEntries(formData.entries());

    try {
        const res = await fetch("api/roles", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });
        const data = await res.json().catch(() => null);

        if (res.ok && data && (data.status === "ok" || data.status === "success")) {
            await refrescarFragmento("cuerpoTablaRoles");
            await refrescarFragmento("contenedorMatrizPermisos");
            await refrescarFragmento("selectRolDefecto");
            mostrarAlertaGestion(alertContainerRoles, data.message || "¡Rol eliminado con éxito!", "success");
        } else {
            mostrarAlertaGestion(alertContainerRoles, data?.message || "Error al eliminar el rol", "error");
        }
    } catch (err) {
        console.error("Error al eliminar rol:", err);
        mostrarAlertaGestion(alertContainerRoles, "Error en la comunicación con el servidor", "error");
    }
});

// -------------------------------------------------------------------------
// MATRIZ DE PERMISOS (AJAX)
// -------------------------------------------------------------------------
const formRolesPermisos = document.getElementById("formRolesPermisos");
if (formRolesPermisos) {
    formRolesPermisos.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formRolesPermisos);

        // Estructurar permisos[id_rol][] en objeto para JSON
        const permisosObj = {};
        for (const [key, val] of formData.entries()) {
            const match = key.match(/^permisos\[(\d+)\]/);
            if (match) {
                const idRol = match[1];
                if (!permisosObj[idRol]) permisosObj[idRol] = [];
                permisosObj[idRol].push(val);
            }
        }

        try {
            const res = await fetch("api/roles/permisos", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ permisos: permisosObj })
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                mostrarAlertaGestion(alertContainerRoles, data.message || "¡Roles y permisos actualizados con éxito!", "success");
            } else {
                mostrarAlertaGestion(alertContainerRoles, data?.message || "Error al guardar permisos", "error");
            }
        } catch (err) {
            console.error("Error al guardar permisos:", err);
            mostrarAlertaGestion(alertContainerRoles, "Error en la comunicación con el servidor", "error");
        }
    });
}

// -------------------------------------------------------------------------
// CONFIGURACIÓN GENERAL (AJAX)
// -------------------------------------------------------------------------
const formGuardarConfiguracion = document.getElementById("formGuardarConfiguracion");
const alertContainerGeneralConfig = document.getElementById("alert-container-general-config");

if (formGuardarConfiguracion) {
    formGuardarConfiguracion.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formGuardarConfiguracion);
        const datos = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("api/configuracion", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                mostrarAlertaGestion(alertContainerGeneralConfig, data.message || "¡Configuración guardada con éxito!", "success");
            } else {
                mostrarAlertaGestion(alertContainerGeneralConfig, data?.message || "Error al guardar configuración", "error");
            }
        } catch (err) {
            console.error("Error al guardar configuración:", err);
            mostrarAlertaGestion(alertContainerGeneralConfig, "Error en la comunicación con el servidor", "error");
        }
    });
}

// -------------------------------------------------------------------------
// CRUD CONDICIONES (AJAX)
// -------------------------------------------------------------------------
const formRegistrarCondicion = document.getElementById("formRegistrarCondicion");
const modalRegistrarCondicion = document.getElementById("modalRegistrarCondicion");
const alertContainerCondiciones = document.getElementById("alert-container-condiciones");

if (formRegistrarCondicion) {
    formRegistrarCondicion.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formRegistrarCondicion);
        const datos = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("api/condiciones", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                formRegistrarCondicion.reset();
                modalRegistrarCondicion?.close?.();
                if (modalRegistrarCondicion) modalRegistrarCondicion.style.opacity = "0";
                await refrescarFragmento("cuerpoTablaCondiciones");
                mostrarAlertaGestion(alertContainerCondiciones, data.message || "¡Condición registrada con éxito!", "success");
            } else {
                alert(data?.message || "Error al registrar la condición");
            }
        } catch (err) {
            console.error("Error al registrar condición:", err);
            alert("Error en la comunicación con el servidor");
        }
    });
}

const formEditarCondicion = document.getElementById("formEditarCondicion");
const modalEditarCondicion = document.getElementById("modalEditarCondicion");

if (formEditarCondicion) {
    formEditarCondicion.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(formEditarCondicion);
        const datos = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("api/condiciones", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data && (data.status === "ok" || data.status === "success")) {
                modalEditarCondicion?.close?.();
                if (modalEditarCondicion) modalEditarCondicion.style.opacity = "0";
                await refrescarFragmento("cuerpoTablaCondiciones");
                mostrarAlertaGestion(alertContainerCondiciones, data.message || "¡Condición actualizada con éxito!", "success");
            } else {
                alert(data?.message || "Error al actualizar la condición");
            }
        } catch (err) {
            console.error("Error al actualizar condición:", err);
            alert("Error en la comunicación con el servidor");
        }
    });
}

document.addEventListener("submit", async (e) => {
    const formEliminarCond = e.target.closest(".form-eliminar-condicion");
    if (!formEliminarCond) return;

    e.preventDefault();
    if (!confirm("¿Seguro que deseas eliminar esta condición?")) return;

    const formData = new FormData(formEliminarCond);
    const datos = Object.fromEntries(formData.entries());

    try {
        const res = await fetch("api/condiciones", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });
        const data = await res.json().catch(() => null);

        if (res.ok && data && (data.status === "ok" || data.status === "success")) {
            await refrescarFragmento("cuerpoTablaCondiciones");
            mostrarAlertaGestion(alertContainerCondiciones, data.message || "¡Condición eliminada con éxito!", "success");
        } else {
            mostrarAlertaGestion(alertContainerCondiciones, data?.message || "Error al eliminar la condición", "error");
        }
    } catch (err) {
        console.error("Error al eliminar condición:", err);
        mostrarAlertaGestion(alertContainerCondiciones, "Error en la comunicación con el servidor", "error");
    }
});

document.addEventListener("click", (e) => {
    const btn = e.target.closest(".editar-rol");
    if (!btn) return;

    const idRol = btn.getAttribute("data-id");
    const nombreRol = btn.getAttribute("data-nombre");
    const descripcionRol = btn.getAttribute("data-descripcion");

    const inputId = document.getElementById("edit_id_rol");
    const inputNombre = document.getElementById("edit_nombre_rol");
    const inputDesc = document.getElementById("edit_descripcion_rol");
    const modal = document.getElementById("modalEditarRol");

    if (inputId && inputNombre && inputDesc && modal) {
        inputId.value = idRol;
        inputNombre.value = nombreRol;
        inputDesc.value = descripcionRol;
        modal.showModal();
        modal.style.opacity = "1";
    }
});