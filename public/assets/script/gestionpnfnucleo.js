$(document).ready(() => {

    const tablaNucleos = new DataTable('#tablaNucleos', {
        autoFill: false,
        searching: true,    
        paging: false,     
        info: false,        
        lengthChange: false,
        ordering: false,
        layout: {
            topStart: 'search',
            topEnd: null
        },
        language: {
        search: 'Buscar:',
        searchPlaceholder: 'Buscar Núcleo',
        zeroRecords: 'No se encontraron núcleos'
    }
});
    const tablaPnfs = new DataTable('#tablaPnfs', {
        autoFill: false,
        searching: true,    
        paging: false,     
        info: false,        
        lengthChange: false,
        ordering: false,
        layout: {
            topStart: 'search',
            topEnd: null
        },
        language: {
        search: 'Buscar:',
        searchPlaceholder: 'Buscar PNF',
        zeroRecords: 'No se encontraron pnfs'
    }
});

    [
        [tablaNucleos, 'Buscar Núcleo'],
        [tablaPnfs, 'Buscar PNF']
    ].forEach(([tabla, placeholder]) => {
        const tablaElemento = tabla.table().node();
        const buscador = tabla.table().container().querySelector('.dt-search');
        const cabecera = tablaElemento.closest('.contenedor-tabla-consultas')?.querySelector('.cabecera-tabla-global');
        const buscadorInput = buscador?.querySelector('input');
        if (cabecera && buscador) {
            cabecera.appendChild(buscador);
            if (buscadorInput) {
                buscadorInput.placeholder = placeholder;
                buscadorInput.style.width = '360px';
                buscadorInput.style.maxWidth = 'calc(100vw - 40px)';
            }
        }
    });

    
async function refrescarContenedor(idContenedor) {
        try {
            const html = await $.get(window.location.href);
            const $doc = $(html);

            const $nuevoElem = $doc.find(`#${idContenedor}`);
            const $actualElem = $(`#${idContenedor}`);

            if ($nuevoElem.length && $actualElem.length) {
                $actualElem.html($nuevoElem.html());
                const $tabla = $actualElem.closest('table').length ? $actualElem.closest('table') : $actualElem;
                $tabla[0]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } catch (err) {
            console.error(`Error al refrescar #${idContenedor}:`, err);
        }
    }

    function mostrarAlerta(contenedorId, mensaje, tipo = 'success') {
        const $contenedor = typeof contenedorId === 'string' ? $(`#${contenedorId}`) : $(contenedorId);
        if (!$contenedor.length) return;

        const colorBorde = tipo === 'success' ? '#2ecc71' : '#e74c3c';

        const $alertaEl = $('<div></div>')
            .addClass('action-card')
            .css({
                'padding': '1rem',
                'border-left': `5px solid ${colorBorde}`,
                'background': '#fdfdfd',
                'width': '100%',
                'box-sizing': 'border-box',
                'margin-bottom': '1rem'
            })
            .html(`
                <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span>${mensaje}</span>
                    <span class="btn-cerrar-alerta" style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;">×</span>
                </p>
            `);

        $alertaEl.find('.btn-cerrar-alerta').on('click', function () {
            $alertaEl.remove();
        });

        $contenedor.append($alertaEl);
        setTimeout(() => $alertaEl.remove(), 2000);
    }

    function mostrarErrorEnFormulario(formulario, mensaje) {
        const $form = $(formulario);
        let $contenedorModal = $form.find('.alert-container-modal');

        if (!$contenedorModal.length) {
            $contenedorModal = $('<div></div>').addClass('alert-container-modal');
            $form.prepend($contenedorModal);
        }

        $contenedorModal.empty();
        mostrarAlerta($contenedorModal, mensaje, 'error');
    }

    $(document).on('click', '[data-modal="modalRegistrarPNF"]', function (e) {
        e.preventDefault();
        const modal = $('#modalRegistrarPNF')[0];
        if (modal) {
            modal.showModal();
            $(modal).css('opacity', '1');
        }
    });

    $(document).on('click', '[data-modal="modalRegistrarNucleo"]', function (e) {
        e.preventDefault();
        const modal = $('#modalRegistrarNucleo')[0];
        if (modal) {
            modal.showModal();
            $(modal).css('opacity', '1');
        }
    });

    $(document).on('click', '.editar-pnf', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const modalEditar = $('#modalActualizarPNF')[0];

        if (modalEditar) {
            $('#edit_id_pnf').val($btn.data('id'));
            $('#edit_nombre_pnf').val($btn.data('nombre') || '');
            modalEditar.showModal();
            $(modalEditar).css('opacity', '1');
        }
    });

    $(document).on('click', '.editar-nucleo', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const modalEditar = $('#modalActualizarNucleo')[0];

        if (modalEditar) {
            $('#edit_id_nucleo').val($btn.data('id'));
            $('#edit_nombre_nucleo').val($btn.data('nombre') || '');
            modalEditar.showModal();
            $(modalEditar).css('opacity', '1');
        }
    });

    $('dialog').on('close', function () {
        $(this).css('opacity', '0');
        $(this).find('.alert-container-modal').empty();
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