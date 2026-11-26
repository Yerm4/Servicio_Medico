$(document).ready(() => {

    const $formRegistrarOferta = $("#formRegistrarOferta");
    const $cuerpoTablaOfertas = $("#cuerpoTablaOfertas");
    const $alertContainerGeneral = $("#alert-container-oferta");
    const $modalRegistrarOferta = $("#modalRegistrarOferta");
    const $selectNucleoOferta = $("#id_nucleo_oferta");
    const $seleccionarTodosPnfs = $("#seleccionarTodosPnfs");

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

            tbody.querySelectorAll('tr.tr-grupo-nucleo').forEach(tr => tr.remove());

            const rows = api.rows({ page: 'current' }).nodes();
            const nucleos = api.column(0, { page: 'current' }).data().toArray();

            const conteo = {};
            nucleos.forEach(n => {
                conteo[n] = (conteo[n] || 0) + 1;
            });

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
                error: function (jqXHR, textStatus) {
                    reject({
                        status: jqXHR.status,
                        statusText: textStatus,
                        responseJSON: jqXHR.responseJSON || null
                    });
                }
            });
        });
    }

    function resetearCheckboxesPnf() {
        $seleccionarTodosPnfs.prop("checked", false);
        $(".pnf-checkbox").each(function () {
            const $cb = $(this);
            const $label = $cb.closest(".pnf-checkbox-label");
            $cb.prop("checked", false).prop("disabled", false);
            $label.css("opacity", "1");
            $label.find(".pnf-badge-asignado").hide();
        });
    }

    // Marcar y bloquear los PNFs que ya están ofertados en el núcleo seleccionado
    $selectNucleoOferta.on("change", async function () {
        const idNucleo = $(this).val();
        resetearCheckboxesPnf();
        if (!idNucleo) return;

        try {
            const resp = await apiFetch(`api/nucleos/pnfs/${encodeURIComponent(idNucleo)}`);
            const asignados = new Set((resp.data || []).map(item => String(item.id_pnf)));

            $(".pnf-checkbox").each(function () {
                const $cb = $(this);
                const $label = $cb.closest(".pnf-checkbox-label");
                if (asignados.has(String($cb.val()))) {
                    $cb.prop("checked", true).prop("disabled", true);
                    $label.css("opacity", "0.65");
                    $label.find(".pnf-badge-asignado").show();
                }
            });
        } catch (err) {
            console.error("No se pudieron cargar los PNFs del núcleo:", err);
        }
    });

    // Seleccionar / deseleccionar todos los PNFs disponibles
    $seleccionarTodosPnfs.on("change", function () {
        const marcar = $(this).is(":checked");
        $(".pnf-checkbox:not(:disabled)").prop("checked", marcar);
    });

    $(document).on("change", ".pnf-checkbox:not(:disabled)", function () {
        const totalDisponibles = $(".pnf-checkbox:not(:disabled)").length;
        const totalMarcados = $(".pnf-checkbox:not(:disabled):checked").length;
        $seleccionarTodosPnfs.prop("checked", totalDisponibles > 0 && totalDisponibles === totalMarcados);
    });

    async function actualizarTablaOfertas() {
        try {
            const response = await $.get(window.location.href);
            const $doc = $(response);
            const filasNuevas = $doc.find("#cuerpoTablaOfertas tr.tr-body-consultas").toArray();

            tablaOfertas.clear();
            if (filasNuevas.length) {
                tablaOfertas.rows.add(filasNuevas);
            }
            tablaOfertas.draw();

            const $tablaObjetivo = $cuerpoTablaOfertas.closest("table");
            if ($tablaObjetivo.length) {
                $tablaObjetivo[0].scrollIntoView({ behavior: "smooth", block: "center" });
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
        setTimeout(() => $alertaEl.remove(), 3000);
    }

    if ($formRegistrarOferta.length) {
        $formRegistrarOferta.on("submit", async function (e) {
            e.preventDefault();

            let $alertContainerModal = $formRegistrarOferta.find(".alert-container-modal");
            if (!$alertContainerModal.length) {
                $alertContainerModal = $("<div></div>").addClass("alert-container-modal");
                $formRegistrarOferta.prepend($alertContainerModal);
            }
            $alertContainerModal.empty();

            const formData = new FormData(this);
            const idNucleo = formData.get("id_nucleo");
            const pnfsSeleccionados = formData.getAll("id_pnf[]");

            if (!idNucleo) {
                mostrarAlertaOferta("Debe seleccionar un núcleo.", "error", $alertContainerModal);
                return;
            }

            if (pnfsSeleccionados.length === 0) {
                mostrarAlertaOferta("Debe seleccionar al menos un PNF nuevo para asignar.", "error", $alertContainerModal);
                return;
            }

            const datos = {
                csrf_token: formData.get("csrf_token"),
                id_nucleo: idNucleo,
                id_pnf: pnfsSeleccionados
            };

            try {
                const result = await apiFetch("api/ofertas", {
                    method: "POST",
                    data: datos
                });

                if (result.status === "ok" || result.status === "success") {
                    this.reset();
                    resetearCheckboxesPnf();
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
                    tablaOfertas.row($fila).remove().draw();
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