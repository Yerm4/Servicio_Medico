
$(document).ready(() => {

    const $formRegistrarOferta = $("#formRegistrarOferta");
    const $cuerpoTablaOfertas = $("#cuerpoTablaOfertas");
    const $alertContainerGeneral = $("#alert-container-oferta");
    const $modalRegistrarOferta = $("#modalRegistrarOferta");
      $(document).on('input', '#buscarOferta', (e) => {
        tablaRegistrosOfertas.search(e.target.value).draw();
    });

const tablaOfertas = new DataTable('#tablaRegistrosOfertas', {
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
        searchPlaceholder: 'Buscar Oferta o Núcleo',
        emptyTable: 'No hay ofertas académicas vinculadas.',
        zeroRecords: 'No se encontraron ofertas'
    },
    columnDefs: [
        {
            targets: 0,
            defaultContent: '',
            render: function (data, type) {
                // Usa el nombre real para buscar, pero en pantalla muestra solo "↳"
                return type === 'display'
                    ? '<span style="color: #94a3b8; padding-left: 1rem;">↳</span>'
                    : data;
            }
        }
    ],
    drawCallback: function () {
        const api = this.api();
        const tbody = document.getElementById('cuerpoTablaOfertas');
        if (!tbody) return;

        // Limpiar cabeceras de grupo previas antes de redibujar
        tbody.querySelectorAll('tr.tr-grupo-nucleo').forEach(tr => tr.remove());

        const rows = api.rows({ page: 'current' }).nodes();
        const nucleos = api.column(0, { page: 'current' }).data().toArray();

        // Contar PNFs visibles por núcleo
        const conteo = {};
        nucleos.forEach(n => {
            conteo[n] = (conteo[n] || 0) + 1;
        });

        // Insertar la fila de cabecera encima del primer PNF de cada núcleo
        let ultimoNucleo = null;
        nucleos.forEach((nucleo, i) => {
            if (ultimoNucleo !== nucleo) {
                const trGrupo = document.createElement('tr');
                trGrupo.className = 'tr-grupo-nucleo';
                trGrupo.style.backgroundColor = '#f1f5f9';
                trGrupo.innerHTML = `
                    <td colspan="3" class="td-consultas" style="font-weight: 700; color: #1e293b; padding: 0.75rem 1rem; text-align: left;">
                        ${nucleo}
                        <span style="font-weight: 400; font-size: 0.85em; color: #64748b;">(${conteo[nucleo]} PNFs)</span>
                    </td>
                `;
                rows[i].parentNode.insertBefore(trGrupo, rows[i]);
                ultimoNucleo = nucleo;
            }
        });
    }
});
    
$('#buscarOfertaInput').on('input', function () {
        const termino = $(this).val().trim().toLowerCase();

    $('#cuerpoTablaOfertas tr.tr-body-consultas').each(function () {
        const $fila = $(this);
        const nucleo = ($fila.attr('data-nucleo') || $fila.find('td:eq(0)').text()).toLowerCase();
        const pnf = $fila.find('td:eq(1)').text().toLowerCase();
        const coincide = !termino || nucleo.includes(termino) || pnf.includes(termino);

        $fila.toggle(coincide);
    });
    });
function apiFetch(url, options = {}) {
    const csrfToken = $('input[name="csrf_token"]').val() || $('meta[name="csrf-token"]').attr('content') || '';
    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: options.method || 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                ...(options.headers || {})
            },
            data: options.data ? JSON.stringify(options.data) : null,
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
    async function actualizarTablaOfertas() {
        try {
            const response = await $.get(window.location.href);
            const $doc = $(response);
            const $nuevaTabla = $doc.find("#cuerpoTablaOfertas");

            if ($nuevaTabla.length && $cuerpoTablaOfertas.length) {
                $cuerpoTablaOfertas.html($nuevaTabla.html());
                const $tablaObjetivo = $cuerpoTablaOfertas.closest("table");
                if ($tablaObjetivo.length) {
                    $tablaObjetivo[0].scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }
        } catch (err) {
            console.error("Error al refrescar la tabla de ofertas:", err);
        }
    }

    function mostrarAlertaOferta(mensaje, tipo = "success", $contenedorEspecifico = null) {
        const $targetContainer = $contenedorEspecifico || $alertContainerGeneral;
        if (!$targetContainer.length) return;

        const colorBorde = tipo === "success" ? "#2ecc71" : "#e74c3c";
        
        const $alertaEl = $("<div></div>")
            .addClass("action-card")
            .css({
                "padding": "1rem",
                "border-left": `5px solid ${colorBorde}`,
                "background": "#fdfdfd",
                "width": "100%",
                "box-sizing": "border-box",
                "margin-bottom": "1rem"
            })
            .html(`
                <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span>${mensaje}</span>
                    <span class="btn-cerrar-alerta" style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;">×</span>
                </p>
            `);

        $alertaEl.find(".btn-cerrar-alerta").on("click", () => $alertaEl.remove());

        $targetContainer.append($alertaEl);
        setTimeout(() => $alertaEl.remove(), 2000);
    }


    if ($formRegistrarOferta.length) {
        $formRegistrarOferta.on("submit", async function (e) {
            e.preventDefault();

            let $alertContainerModal = $formRegistrarOferta.find(".alert-container-modal");
            if (!$alertContainerModal.length) {
                $alertContainerModal = $("<div></div>").addClass("alert-container-modal");
                $formRegistrarOferta.prepend($alertContainerModal);
            }

            try {
                const formData = new FormData(this);
                const datos = Object.fromEntries(formData.entries());

                const result = await apiFetch("api/ofertas", {
                    method: "POST",
                    data: datos
                });

                if (result.status === "ok" || result.status === "success") {
                    this.reset();
                    $alertContainerModal.empty();
                    
                    if ($modalRegistrarOferta.length && typeof $modalRegistrarOferta[0].close === 'function') {
                        $modalRegistrarOferta[0].close();
                    }

                    await actualizarTablaOfertas();
                    mostrarAlertaOferta(result.message || "Oferta académica vinculada con éxito.", "success", $alertContainerGeneral);
                } else {
                    mostrarAlertaOferta(result.message || "Hubo un error al registrar la oferta.", "error", $alertContainerModal);
                }
            } catch (error) {
                console.error("Error:", error);
                const mensajeError = error.responseJSON?.message || error.message || "Ocurrió un error en la comunicación con el servidor.";
                mostrarAlertaOferta(mensajeError, "error", $alertContainerModal);
            }
        });
    }

    // ELIMINAR OFERTA ACADÉMICA 
    if ($cuerpoTablaOfertas.length) {
        $cuerpoTablaOfertas.on("submit", "form", async function (e) {
            e.preventDefault();

            if (!confirm("¿Seguro que desea eliminar esta oferta académica?")) return;
            
            try {
                const $form = $(this);
                const formData = new FormData($form[0]);
                const datos = Object.fromEntries(formData.entries());
                const $fila = $form.closest("tr");

                const result = await apiFetch("api/ofertas", {
                    method: "DELETE",
                    data: datos
                });

                if (result.status === "ok") {
                    $fila.remove();

                    if ($cuerpoTablaOfertas.find("tr").length === 0) {
                        $cuerpoTablaOfertas.html(`
                            <tr>
                                <td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas actualmente.</td>
                            </tr>
                        `);
                    }
                    mostrarAlertaOferta(result.message || "Oferta eliminada con éxito.", "success", $alertContainerGeneral);
                } else {
                    mostrarAlertaOferta(result.message || "Hubo un error al procesar la solicitud.", "error", $alertContainerGeneral);
                }
            } catch (error) {
                console.error("Error:", error);
                const mensajeError = error.responseJSON?.message || error.message || "Ocurrió un error en la comunicación con el servidor.";
                mostrarAlertaOferta(mensajeError, "error", $alertContainerGeneral);
            }
        });
    }
});