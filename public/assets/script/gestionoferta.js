document.addEventListener("DOMContentLoaded", function() {
    const formRegistrarOferta = document.getElementById("formRegistrarOferta");
    const cuerpoTablaOfertas = document.getElementById("cuerpoTablaOfertas");
    const alertContainerGeneral = document.getElementById("alert-container-oferta");
    const modalRegistrarOferta = document.getElementById("modalRegistrarOferta");

    // =========================================================================
    // FUNCIÓN PARA REFRESCAR LA TABLA DE OFERTAS DESDE EL SERVIDOR
    // =========================================================================
    function actualizarTablaOfertas() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const nuevaTabla = doc.getElementById('cuerpoTablaOfertas');
                const contenedorActual = document.getElementById('cuerpoTablaOfertas');
                
                if (nuevaTabla && contenedorActual) {
                    contenedorActual.innerHTML = nuevaTabla.innerHTML;

                    // REDIRECCIÓN VISUAL INSTANTÁNEA: Mover la pantalla suavemente a la tabla
                    const tablaObjetivo = contenedorActual.closest('table') || contenedorActual;
                    if (tablaObjetivo) {
                        tablaObjetivo.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            })
            .catch(err => console.error("Error al refrescar la tabla de ofertas:", err));
    }

    // =========================================================================
    // FUNCIÓN AUXILIAR MEJORADA PARA RENDERIZAR ALERTAS (CON AUTO-BORRADO GARANTIZADO)
    // =========================================================================
    function mostrarAlertaOferta(mensaje, tipo = "success", contenedorEspecifico = null) {
        const targetContainer = contenedorEspecifico || alertContainerGeneral;
        if (!targetContainer) return;
        
        // Inyectamos el componente con un ID único para rastrear su eliminación exacta
        const idAlerta = 'alerta_' + Date.now();

        targetContainer.innerHTML = `
            <div id="${idAlerta}" class="action-card" style="padding: 1rem; border-left: 5px solid ${tipo === 'success' ? '#2ecc71' : '#e74c3c'}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;">
                <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span>${mensaje}</span>
                    <span style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;" onclick="this.closest('.action-card').remove()">×</span>
                </p>
            </div>
        `;

        // Temporizador de desvanecimiento forzado (4 segundos) tanto para éxito como error
        setTimeout(() => {
            const alertaActiva = document.getElementById(idAlerta);
            if (alertaActiva) {
                alertaActiva.remove();
            }
        }, 4000);
    }

    // =========================================================================
    // 1. REGISTRAR OFERTA ACADÉMICA (FORMULARIO DEL MODAL)
    // =========================================================================
    if (formRegistrarOferta) {
        formRegistrarOferta.addEventListener("submit", function(e) {
            e.preventDefault();

            let alertContainerModal = formRegistrarOferta.querySelector(".alert-container-modal");
            if (!alertContainerModal) {
                alertContainerModal = document.createElement("div");
                alertContainerModal.className = "alert-container-modal";
                formRegistrarOferta.insertBefore(alertContainerModal, formRegistrarOferta.firstChild);
            }

            const formData = new FormData(formRegistrarOferta);

            fetch("index.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // 1. Limpiamos campos y vaciamos rastros de errores internos
                    formRegistrarOferta.reset();
                    alertContainerModal.innerHTML = '';
                    
                    // 2. Cerramos la ventana modal de forma limpia
                    if (modalRegistrarOferta && typeof modalRegistrarOferta.close === "function") {
                        modalRegistrarOferta.close();
                    }
                    
                    // 3. Refrescamos asíncronamente el contenedor de la tabla
                    actualizarTablaOfertas();
                    
                    // 4. Mostramos la alerta de éxito AL INSTANTE en la interfaz principal
                    mostrarAlertaOferta(data.message || "Oferta académica vinculada con éxito.", "success", alertContainerGeneral);

                } else {
                    // SI HAY ERROR: NO cerramos el modal, mostramos el error adentro AL INSTANTE
                    mostrarAlertaOferta(data.message || "Hubo un error al registrar la oferta.", "error", alertContainerModal);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error", alertContainerModal);
            });
        });
    }

    // =========================================================================
    // 2. ELIMINAR / DESVINCULAR OFERTA ACADÉMICA (TABLA)
    // =========================================================================
    if (cuerpoTablaOfertas) {
        cuerpoTablaOfertas.addEventListener("submit", function(e) {
            const form = e.target;
            const inputFormType = form.querySelector('input[name="form"]');

            if (inputFormType && inputFormType.value === "eliminar_oferta") {
                e.preventDefault();

                if (!confirm("¿Seguro que desea eliminar esta oferta académica?")) {
                    return;
                }

                const formData = new FormData(form);
                const fila = form.closest("tr");

                fetch("index.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        fila.remove();
                        
                        if (cuerpoTablaOfertas.querySelectorAll("tr").length === 0) {
                            cuerpoTablaOfertas.innerHTML = `
                                <tr>
                                    <td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas actualmente.</td>
                                </tr>
                            `;
                        }
                        
                        // Muestra la alerta de eliminación exitosa al instante
                        mostrarAlertaOferta(data.message || "Oferta eliminada con éxito.", "success", alertContainerGeneral);
                    } else {
                        mostrarAlertaOferta(data.message || "Hubo un error al procesar la solicitud.", "error", alertContainerGeneral);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error", alertContainerGeneral);
                });
            }
        });
    }
});