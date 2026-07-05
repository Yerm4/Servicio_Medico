document.addEventListener("DOMContentLoaded", function() {
    const formRegistrarOferta = document.getElementById("formRegistrarOferta");
    const cuerpoTablaOfertas = document.getElementById("cuerpoTablaOfertas");
    const alertContainer = document.getElementById("alert-container-oferta");

    // Función auxiliar para renderizar las alertas con tu diseño personalizado
    function mostrarAlertaOferta(mensaje, tipo = "success") {
        if (!alertContainer) return;
        
        alertContainer.innerHTML = `
            <div class="action-card" style="padding: 1rem; border-left: 5px solid ${tipo === 'success' ? '#2ecc71' : '#e74c3c'}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;">
                <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span>${mensaje}</span>
                    <span style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;" onclick="this.closest('.action-card').remove()">×</span>
                </p>
            </div>
        `;
    }

    // =========================================================================
    // 1. REGISTRAR OFERTA ACADÉMICA (FORMULARIO DEL MODAL)
    // =========================================================================
    if (formRegistrarOferta) {
   formRegistrarOferta.addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(formRegistrarOferta);

            fetch("index.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    mostrarAlertaOferta(data.message || "Oferta académica vinculada con éxito.", "success");
                    
                    // Cierra la modal automáticamente antes de recargar
                    if (modalRegistrarOferta && typeof modalRegistrarOferta.close === "function") {
                        modalRegistrarOferta.close();
                    }

                    // Espera 1.5 segundos para que se logre leer la alerta
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);
                } else {
                    mostrarAlertaOferta(data.message || "Hubo un error al registrar la oferta.", "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error");
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

                if (!confirm("¿Seguro que deseas desvincular esta oferta académica?")) {
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
                        
                        // Si la tabla se queda vacía, insertamos el mensaje de "No hay registros"
                        if (cuerpoTablaOfertas.querySelectorAll("tr").length === 0) {
                            cuerpoTablaOfertas.innerHTML = `
                                <tr>
                                    <td colspan="3" class="td-tabla-vacia">No hay ofertas académicas vinculadas actualmente.</td>
                                </tr>
                            `;
                        }
                        
                        mostrarAlertaOferta(data.message || "Oferta eliminada con éxito.", "success");
                    } else {
                        mostrarAlertaOferta(data.message || "Hubo un error al procesar la solicitud.", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    mostrarAlertaOferta("Ocurrió un error en la comunicación con el servidor.", "error");
                });
            }
        });
    }
});
