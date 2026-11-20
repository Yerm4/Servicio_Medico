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

function escaparHtml(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, (caracter) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caracter]);
}

const ICONO_VER = `<svg style="pointer-events:none" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="13" r="2" stroke="#000" stroke-linejoin="round"/><path d="M12 7.5C7.69517 7.5 4.47617 11.0833 3.39473 12.4653C3.14595 12.7832 3.14595 13.2168 3.39473 13.5347C4.47617 14.9167 7.69517 18.5 12 18.5C16.3048 18.5 19.5238 14.9167 20.6053 13.5347C20.8541 13.2168 20.8541 12.7832 20.6053 12.4653C19.5238 11.0833 16.3048 7.5 12 7.5Z" stroke="#000" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
const ICONO_EDITAR = `<svg style="pointer-events:none" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000"/></svg>`;

const cuerpoTablaConsultas = document.getElementById('cuerpoTablaConsultas');
const modalActualizarConsulta = document.getElementById('modalActualizarConsulta');
let tablaConsultas = null;

if (cuerpoTablaConsultas && typeof DataTable === 'function') {
    tablaConsultas = new DataTable('#tablaRegistrosConsultas', {
        processing: true,
        serverSide: true,
        ajax: {
            url: 'api/consultas/listado',
            type: 'GET',
            dataSrc: 'data'
        },
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],
        language: {
            processing: 'Procesando...',
            search: 'Buscar:',
            searchPlaceholder: 'Buscar consulta por paciente, médico, cédula o motivo',
            lengthMenu: 'Mostrar _MENU_ consultas',
            info: 'Mostrando _START_-_END_ de _TOTAL_ consultas',
            infoEmpty: 'No hay consultas',
            zeroRecords: 'No se encontraron consultas',
            paginate: {
                first: 'Primero',
                previous: 'Anterior',
                next: 'Siguiente',
                last: 'Último'
            }
        },
        columns: [
            { data: 'fecha_consulta', name: 'fecha_consulta', render: (value) => {
                const date = new Date(value);
                return isNaN(date) ? value : `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
            }},
            { data: null, name: 'paciente', render: (value, type, row) => `<strong>${escaparHtml(row.paciente_nombre || '')} ${escaparHtml(row.paciente_apellido || '')}</strong><div class="td-paciente-sub">C.I. ${escaparHtml(row.id_usuario)}</div>` },
            { data: null, name: 'medico', render: (value, type, row) => `${escaparHtml(row.medico_nombre || '')} ${escaparHtml(row.medico_apellido || '')}` },
            { data: 'motivo_de_visita', name: 'motivo_de_visita', render: (value) => escaparHtml(value) },
            { data: 'sintomas', name: 'sintomas', orderable: false, render: (value) => value && value.length ? value.map(escaparHtml).join(', ') : '<span class="sintomas-ninguno">Ninguno</span>' },
            { data: 'diagnosticos', name: 'diagnosticos', orderable: false, render: (value) => value && value.length ? value.map(d => `<div class="diagnostico-item-tabla"><strong class="diagnostico-codigo">${escaparHtml(d.codigo_icd_diagnostico)}</strong> - ${escaparHtml(d.patologia || 'Sin detalle')}</div>`).join('') : '<span class="sintomas-ninguno">Sin diagnóstico</span>' },
            { data: 'id', name: 'acciones', orderable: false, searchable: false, render: (value) => `<div class="acciones-flex"><button class="ver-detalles-consulta action-card__button btn-detalles-consulta btn-accion-tabla" title="Ver detalles" aria-label="Ver detalles" data-id="${value}">${ICONO_VER}</button>${ES_MEDICO_O_DIRECTOR ? `<button class="editar-consulta action-card__button btn-accion-tabla" title="Editar consulta" aria-label="Editar consulta" data-id="${value}">${ICONO_EDITAR}</button>` : ''}</div>` }
        ]
    });

    const buscadorCaja = document.querySelector('.buscador-caja');
    const buscador = tablaConsultas.table().container().querySelector('.dt-search');
    if (buscadorCaja && buscador) {
        buscadorCaja.appendChild(buscador);
        const buscadorInput = buscador.querySelector('input');
        if (buscadorInput) {
            buscadorInput.placeholder = 'Buscar consulta por paciente, médico, cédula o motivo';
            buscadorInput.style.width = '500px';
            buscadorInput.style.maxWidth = 'calc(100vw - 40px)';
        }
    }
}

if (cuerpoTablaConsultas && modalActualizarConsulta) {
    cuerpoTablaConsultas.addEventListener('click', function(event) {
        if (event.target.classList.contains('ver-detalles-consulta')) {
            event.preventDefault();
            const idConsulta = event.target.getAttribute('data-id');
            const modalVer = document.getElementById('modalVerDetallesConsulta');

            fetch(`api/consultas/${idConsulta}`)
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

            fetch(`api/consultas/${idConsulta}`)
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

function formDataToObject(formData) {
    const data = {};

    for (const [key, value] of formData.entries()) {
        if (key.endsWith("[]")) {
            const name = key.slice(0, -2);
            if (!Array.isArray(data[name])) data[name] = [];
            data[name].push(value);
        } else {
            data[key] = value;
        }
    }

    return data;
}

async function enviarFormularioConsulta(form, method, url, modal) {
    const formData = new FormData(form);
    const response = await fetch(url, {
        method,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formDataToObject(formData))
    });
    const data = await response.json().catch(() => null);

    if (!response.ok || !data || (data.status !== "ok" && data.status !== "success")) {
        throw new Error(data?.message || "No se pudo guardar la consulta");
    }

    modal?.close?.();
    if (modal) modal.style.opacity = "0";
    tablaConsultas?.ajax.reload(null, false);
}

const formRegistrarConsulta = document.querySelector("#modalRegistrarConsulta form");
const modalRegistrarConsulta = document.getElementById("modalRegistrarConsulta");
if (formRegistrarConsulta) {
    formRegistrarConsulta.addEventListener("submit", async (event) => {
        event.preventDefault();

        try {
            await enviarFormularioConsulta(formRegistrarConsulta, "POST", "api/consultas", modalRegistrarConsulta);
        } catch (error) {
            alert(error.message);
        }
    });
}

const formActualizarConsulta = document.getElementById("formulario-edicion-consulta");
if (formActualizarConsulta) {
    formActualizarConsulta.addEventListener("submit", async (event) => {
        event.preventDefault();

        try {
            await enviarFormularioConsulta(formActualizarConsulta, "PUT", "api/consultas", modalActualizarConsulta);
        } catch (error) {
            alert(error.message);
        }
    });
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
                const modalRegistrarRol = document.getElementById("modalRegistrarRol");
                if (modalRegistrarRol?.open) modalRegistrarRol.close();
                if (DataTable.isDataTable('#tablaRoles')) {
                    new DataTable.Api('#tablaRoles').rows().invalidate('dom').draw(false);
                }
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
                body: JSON.stringify({
                    csrf_token: formData.get("csrf_token"),
                    permisos: permisosObj
                })
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

function configurarOrdenamientoTablas() {
    document.querySelectorAll("table").forEach((tabla) => {
        if (tabla.id === "tablaRoles" || tabla.id === "tablaCondiciones" || tabla.id === "tablaRegistrosOfertas" || tabla.id === "tablaRegistros" || tabla.id === "tablaRegistrosConsultas") return;
        const encabezados = tabla.querySelectorAll("thead th.sortable");
        const cuerpo = tabla.tBodies[0];
        if (!encabezados.length || !cuerpo) return;

        encabezados.forEach((encabezado) => {
            encabezado.style.cursor = "pointer";
            encabezado.title = "Ordenar";
            encabezado.addEventListener("click", () => {
                const indice = encabezado.cellIndex;
                const direccionActual = encabezado.dataset.sortDirection === "asc" ? "desc" : "asc";

                encabezados.forEach((otro) => {
                    delete otro.dataset.sortDirection;
                    otro.textContent = otro.textContent.replace(/[▲▼]\s*$/, "").trim();
                });

                encabezado.dataset.sortDirection = direccionActual;
                encabezado.textContent = `${encabezado.textContent.replace(/[▲▼]\s*$/, "").trim()} ${direccionActual === "asc" ? "▲" : "▼"}`;

                const filas = Array.from(cuerpo.rows)
                    .filter((fila) => !fila.querySelector(".td-tabla-vacia") && fila.cells.length > indice)
                    .map((fila, posicion) => ({ fila, posicion }));

                filas.sort((a, b) => {
                    const valorA = a.fila.cells[indice].textContent.trim();
                    const valorB = b.fila.cells[indice].textContent.trim();
                    const numeroA = Number(valorA);
                    const numeroB = Number(valorB);
                    const ambosNumericos = valorA !== "" && valorB !== "" && Number.isFinite(numeroA) && Number.isFinite(numeroB);
                    const comparacion = ambosNumericos
                        ? numeroA - numeroB
                        : valorA.localeCompare(valorB, undefined, { sensitivity: "base" });

                    return (direccionActual === "asc" ? comparacion : -comparacion) || a.posicion - b.posicion;
                });

                filas.forEach(({ fila }) => cuerpo.appendChild(fila));
            });
        });
    });
}

configurarOrdenamientoTablas();

if (typeof DataTable === 'function') {
    const placeholders = {
        tablaRoles: 'Buscar roles',
        tablaRolesPermisos: 'Buscar permisos',
        tablaCondiciones: 'Buscar condiciones',
        tablaRegistrosOfertas: 'Buscar ofertas académicas'
    };

    ['tablaRoles', 'tablaRolesPermisos', 'tablaCondiciones', 'tablaRegistrosOfertas'].forEach((id) => {
        const tabla = document.getElementById(id);
        if (!tabla || DataTable.isDataTable(tabla)) return;

        const instancia = new DataTable(tabla, {
            paging: false,
            info: false,
            lengthChange: false,
            language: {
                search: 'Buscar:',
                searchPlaceholder: placeholders[id],
                zeroRecords: 'No se encontraron registros',
                emptyTable: 'No hay registros'
            },
            layout: {
                topStart: 'search',
                topEnd: null
            }
        });

        const buscador = instancia.table().container().querySelector('.dt-search');
        const contenedorRoles = id === 'tablaRoles' ? tabla.closest('.contenedor-roles-registrados') : null;
        const contenedorPermisos = id === 'tablaRolesPermisos' ? tabla.closest('#formRolesPermisos') : null;
        const cabeceraObjetivo = contenedorRoles?.querySelector('.cabecera-tabla-global')
            || contenedorPermisos?.querySelector('.cabecera-tabla-global')
            || tabla.closest('.sub-tab-content')?.querySelector('.cabecera-tabla-global');
        const buscadorInput = buscador?.querySelector('input');

        if (cabeceraObjetivo && buscador) {
            cabeceraObjetivo.appendChild(buscador);
            if (buscadorInput) {
                buscadorInput.placeholder = placeholders[id];
                buscadorInput.style.width = '360px';
                buscadorInput.style.maxWidth = 'calc(100vw - 40px)';
            }
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