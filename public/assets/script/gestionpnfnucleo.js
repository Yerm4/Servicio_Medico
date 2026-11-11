$(document).ready(() => {

    async function refrescarContenedor(idContenedor) {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const nuevoElem = doc.getElementById(idContenedor);
            const actualElem = document.getElementById(idContenedor);

            if (nuevoElem && actualElem) {
                actualElem.innerHTML = nuevoElem.innerHTML;
                const tabla = actualElem.closest('table') || actualElem;
                tabla?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } catch (err) {
            console.error(`Error al refrescar #${idContenedor}:`, err);
        }
    }


    function mostrarAlerta(contenedorId, mensaje, tipo = 'success') {
        const contenedor = typeof contenedorId === 'string' ? document.getElementById(contenedorId) : contenedorId;
        if (!contenedor) return;

        const colorBorde = tipo === 'success' ? '#2ecc71' : '#e74c3c';
        const alertaEl = document.createElement('div');
        alertaEl.className = 'action-card';
        alertaEl.style.cssText = `padding: 1rem; border-left: 5px solid ${colorBorde}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;`;

        alertaEl.innerHTML = `
            <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <span>${mensaje}</span>
                <span style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;" onclick="this.closest('.action-card').remove()">×</span>
            </p>
        `;

        contenedor.appendChild(alertaEl);
        setTimeout(() => alertaEl.remove(), 4000);
    }

    function mostrarErrorEnFormulario(formulario, mensaje) {
        let contenedorModal = formulario.querySelector('.alert-container-modal');
        if (!contenedorModal) {
            contenedorModal = document.createElement('div');
            contenedorModal.className = 'alert-container-modal';
            formulario.insertBefore(contenedorModal, formulario.firstChild);
        }
        contenedorModal.innerHTML = '';
        mostrarAlerta(contenedorModal, mensaje, 'error');
    }

    async function apiFetch(url, options = {}) {
        const response = await fetch(url, options);
        const result = await response.json().catch(() => null);

        if (!response.ok || !result) {
            const errorMsg = result?.message || `${response.status}: ${response.statusText}`;
            throw new Error(errorMsg);
        }
        return result;
    }


    // CONTROL DE MODALES 
    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-modal="modalRegistrarPNF"]')) {
            e.preventDefault();
            const modal = $(`#${modalRegistrarPNF}`);
            modal?.showModal();
            if (modal) modal.style.opacity = '1';
        }

        if (e.target.closest('[data-modal="modalRegistrarNucleo"]')) {
            e.preventDefault();
            const modal = $(`#${modalRegistrarNucleo}`);
            modal?.showModal();
            if (modal) modal.style.opacity = '1';
        }

        const btnEditarPnf = e.target.closest('.editar-pnf');
        if (btnEditarPnf) {
            e.preventDefault();
            const idPnf = btnEditarPnf.getAttribute('data-id');
            const nombrePnf = btnEditarPnf.getAttribute('data-nombre');
            const modalEditar = $(`#${modalActualizarPNF}`);

            if (modalEditar) {  
                document.getElementById('edit_id_pnf').value = idPnf;
                document.getElementById('edit_nombre_pnf').value = nombrePnf || '';
                modalEditar.showModal();
                modalEditar.style.opacity = '1';
            }
        }

        const btnEditarNucleo = e.target.closest('.editar-nucleo');
        if (btnEditarNucleo) {
            e.preventDefault();
            const idNucleo = btnEditarNucleo.getAttribute('data-id');
            const nombreNucleo = btnEditarNucleo.getAttribute('data-nombre');
            const modalEditar = document.getElementById('modalActualizarNucleo');

            if (modalEditar) {
                document.getElementById('edit_id_nucleo').value = idNucleo;
                document.getElementById('edit_nombre_nucleo').value = nombreNucleo || '';
                modalEditar.showModal();
                modalEditar.style.opacity = '1';
            }
        }
    });

    document.querySelectorAll('dialog').forEach(modal => {
        modal.addEventListener('close', () => {
            modal.style.opacity = '0';
            const alertModal = modal.querySelector('.alert-container-modal');
            if (alertModal) alertModal.innerHTML = '';
        });
    });


function apiFetch(url, options = {}) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: options.method || 'GET',
            data: options.data || null,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            success: function (response) {
                resolve(response); 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                reject({
                    status: jqXHR.status,
                    statusText: textStatus,
                    responseJSON: jqXHR.responseJSON || null
                });
            }
        });
    });
    }

        // LOGICA CRUD: PNF
    // 1. REGISTRAR Y ACTUALIZAR PNF
const configurarFormularioPNF = (idForm, idModal, msgExito, metodo) => { 
    const $form = $(`#${idForm}`);
    if ($form.length === 0) return; 

    $form.on('submit', async function (e) {
        e.preventDefault();

        const datos = Object.fromEntries(new FormData(this).entries());
        const $modal = $(`#${idModal}`);

        try {
            const result = await apiFetch('api/pnfs', {
                method: metodo,
                data: JSON.stringify(datos)
            });

            if (result.status === 'ok') {
                this.reset();

                if ($modal.length > 0) { 
                    $modal.css('opacity', '0');
                    $modal.get(0).close();
                }
                await refrescarContenedor('tabla-pnf-body'); 
                mostrarAlerta('alert-container-pnf', result.message || msgExito, 'success');
            } else {
                mostrarErrorEnFormulario(this, result.message || "Ocurrió un error.");
            }

        } catch (error) {
            console.error("Error en PNF:", error);
            const mensajeError = error.responseJSON?.message || "Ocurrió un error al procesar el PNF.";
            mostrarErrorEnFormulario(this, mensajeError);
        }
    });
};

configurarFormularioPNF('formRegistrarPNF', 'modalRegistrarPNF', "¡PNF registrado con éxito!", 'POST');
configurarFormularioPNF('formActualizarPNF', 'modalActualizarPNF', "¡PNF actualizado con éxito!", 'PUT');


    // LOGICA CRUD: NÚCLEOS
    // 1. REGISTRAR Y ACTUALIZAR NÚCLEOS
    const configurarFormularioNucleo = (idForm, idModal, msgExito, metodo) => { 
    const $form = $(`#${idForm}`);
    if ($form.length === 0) return; 

    $form.on('submit',  async function (e) {
        e.preventDefault();

        const datos = Object.fromEntries(new FormData(this).entries());
        const $modal = $(`#${idModal}`);
        
        try {
            const result = await apiFetch('api/nucleos', {
                method: metodo,
                data: JSON.stringify(datos)
            });

            if (result.status === 'ok') {
                this.reset();

                if ($modal.length > 0) {
                    $modal.css('opacity', '0');
                    $modal.get(0).close();
                }
                await refrescarContenedor('cuerpoTablaNucleos');
                mostrarAlerta('alert-container-nucleo', result.message || msgExito, 'success');
            } else {
                mostrarErrorEnFormulario(this, result.message || "Ocurrio un error.")
            }
        } catch (error) {
            console.error("Error en Nucleo:", error);
            const mensajeError = error.responseJSON?.message || "Ocurrio un error al procesar el nucleo.";
            mostrarErrorEnFormulario(this, mensajeError);
        }
    });
};
    configurarFormularioNucleo('formRegistrarNucleo', 'modalRegistrarNucleo', "¡Núcleo registrado con éxito!", 'POST');
    configurarFormularioNucleo('formActualizarNucleo', 'modalActualizarNucleo', "¡Núcleo actualizado con éxito!", 'PUT');

    // ELIMINACIONES (PNF Y NÚCLEOS)

$(document).on('click', '[name="eliminarNucleo"]', async function (e) {
    e.preventDefault();

    const $btn = $(this);
    const idNucleo = $btn.data('id');

    if (!confirm('¿Seguro que deseas eliminar este núcleo?')) return;

    try {
        const result = await apiFetch(`api/nucleos/${idNucleo}`, { method: 'DELETE' });

        if (result.status === 'ok') {
            await refrescarContenedor('cuerpoTablaNucleos');
            mostrarAlerta('alert-container-nucleo', result.message || "¡Núcleo eliminado con éxito!", 'success');
        } else {
            mostrarAlerta('alert-container-nucleo', result.message || "Error al eliminar Núcleo.", 'error');
        }

    } catch (error) {
        console.error("Error al eliminar Núcleo:", error);
        const mensajeError = error.responseJSON?.message || error.message || "Error en la solicitud.";
        mostrarAlerta('alert-container-nucleo', mensajeError, 'error');
    }
});

$(document).on('click', '[name="eliminarPnf"]', async function (e) {
    e.preventDefault();

    const $btn = $(this);
    const idPnf = $btn.data('id');

    if (!confirm('¿Seguro que deseas eliminar este PNF?')) return;

    try {
        const result = await apiFetch(`api/pnfs/${idPnf}`, { method: 'DELETE' });

        if (result.status === 'ok') {
            await refrescarContenedor('tabla-pnf-body');
            mostrarAlerta('alert-container-pnf', result.message || "¡PNF eliminado con éxito!", 'success');
        } else {
            mostrarAlerta('alert-container-pnf', result.message || "Error al eliminar PNF.", 'error');
        }

    } catch (error) {
        console.error("Error al eliminar PNF:", error);
        const mensajeError = error.responseJSON?.message || error.message || "Error en la solicitud.";
        mostrarAlerta('alert-container-pnf', mensajeError, 'error');
    }
});
});