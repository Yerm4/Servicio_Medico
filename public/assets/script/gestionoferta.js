document.addEventListener("DOMContentLoaded", () => {
    // -------------------------------------------------------------------------
    // ELEMENTOS DEL DOM
    // -------------------------------------------------------------------------
    const formRegistrarOferta = document.getElementById("formRegistrarOferta");
    const cuerpoTablaOfertas = document.getElementById("cuerpoTablaOfertas");
    const alertContainerGeneral = document.getElementById("alert-container-oferta");
    const modalRegistrarOferta = document.getElementById("modalRegistrarOferta");

    // -------------------------------------------------------------------------
    // FUNCIONES AUXILIARES
    // -------------------------------------------------------------------------

    /**
     * Refresca el contenido de la tabla realizando una petición GET a la página actual.
     */
    async function actualizarTablaOfertas() {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const nuevaTabla = doc.getElementById("cuerpoTablaOfertas");
            const contenedorActual = document.getElementById("cuerpoTablaOfertas");

            if (nuevaTabla && contenedorActual) {
                contenedorActual.innerHTML = nuevaTabla.innerHTML;
                const tablaObjetivo = contenedorActual.closest("table") || contenedorActual;
                tablaObjetivo?.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        } catch (err) {
            console.error("Error al refrescar la tabla de ofertas:", err);
        }
    }

    /**
     * Muestra alertas visuales dinámicas con desvanecimiento automático.
     */
    function mostrarAlertaOferta(mensaje, tipo = "success", contenedorEspecifico = null) {
        const targetContainer = contenedorEspecifico || alertContainerGeneral;
        if (!targetContainer) return;

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

        targetContainer.appendChild(alertaEl);

        setTimeout(() => alertaEl.remove(), 4000);
    }

    // -------------------------------------------------------------------------
    // EVENTOS API REST
    // -------------------------------------------------------------------------

    // 1. REGISTRAR OFERTA ACADÉMICA (POST api/ofertas)
    if (formRegistrarOferta) {
        formRegistrarOferta.addEventListener("submit", async (e) => {
            e.preventDefault();

            let alertContainerModal = formRegistrarOferta.querySelector(".alert-container-modal");
            if (!alertContainerModal) {
                alertContainerModal = document.createElement("div");
                alertContainerModal.className = "alert-container-modal";
                formRegistrarOferta.insertBefore(alertContainerModal, formRegistrarOferta.firstChild);
            }

            try {
                const formData = new FormData(formRegistrarOferta);
                const datos = Object.fromEntries(formData.entries());

                const response = await fetch("api/ofertas", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datos)
                });

                const data = await response.json();

                if (data.status === "ok" || data.status === "success") {
                    formRegistrarOferta.reset();
                    alertContainerModal.innerHTML = "";
                    modalRegistrarOferta?.close?.();

                    await actualizarTablaOfertas();
                    mostrarAlertaOferta(data.message || "Oferta académica vinculada con éxito.", "success", alertContainerGeneral);
                } else {
                    mostrarAlertaOferta(data.message || "Hubo un error al registrar la oferta.", "error", alertContainerModal);
                }
            } catch (error) {
                console.error("Error:", error);
                mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error", alertContainerModal);
            }
        });
    }

    // 2. ELIMINAR OFERTA ACADÉMICA (DELETE api/ofertas)
    if (cuerpoTablaOfertas) {
        cuerpoTablaOfertas.addEventListener("submit", async (e) => {
            e.preventDefault();

            if (!confirm("¿Seguro que desea eliminar esta oferta académica?")) return;
            
            try {
                const form = e.target;
                const formData = new FormData(form);
                const datos = Object.fromEntries(formData.entries());
                const fila = form.closest("tr");

                const response = await fetch("api/ofertas", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datos)
                });
                const result = await response.json().catch(() => null)

                if (!response.ok) {
                    const error = result?.message || response.status + ": " + response.statusText
                    alert(error)
                    throw new Error(error)
                }

                if (!result) throw new Error("La respuesta no es JSON")

                if (result.status === "ok") {
                    fila?.remove();

                    if (cuerpoTablaOfertas.querySelectorAll("tr").length === 0) {
                        cuerpoTablaOfertas.innerHTML = `
                            <tr>
                                <td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas actualmente.</td>
                            </tr>
                        `;
                    }
                    mostrarAlertaOferta(result.message || "Oferta eliminada con éxito.", "success", alertContainerGeneral);
                } else {
                    mostrarAlertaOferta(result.message || "Hubo un error al procesar la solicitud.", "error", alertContainerGeneral);
                }
            } catch (error) {
                console.error("Error:", error);
                mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error", alertContainerGeneral);
            }
        });
    }
});