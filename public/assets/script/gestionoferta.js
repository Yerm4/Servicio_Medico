function apiFetch(url, options = {}) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: options.method || 'GET',
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

$(document).ready(() => {

    const $formRegistrarOferta = $("#formRegistrarOferta");
    const $cuerpoTablaOfertas = $("#cuerpoTablaOfertas");
    const $alertContainerGeneral = $("#alert-container-oferta");
    const $modalRegistrarOferta = $("#modalRegistrarOferta");

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
        setTimeout(() => $alertaEl.remove(), 4000);
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