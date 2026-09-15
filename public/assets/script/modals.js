document.addEventListener("DOMContentLoaded", () => {
    const modales = document.querySelectorAll(".modal-crud");

    // 1. Abrir modal mediante atributo data-modal
    document.addEventListener("click", (event) => {
        const boton = event.target.closest("[data-modal]");
        if (boton) {
            event.preventDefault();
            const modalId = boton.dataset.modal;
            const modalAbrir = document.getElementById(modalId);
            
            if (modalAbrir) {
                try {
                    modalAbrir.showModal();
                    modalAbrir.style.opacity = "1";
                } catch (e) {
                    console.error("Error al abrir modal:", e);
                }
            }
        }
    });

    // 2. Cierre suave auxiliar
    function cerrarModalConAnimacion(modal) {
        if (!modal) return;
        modal.style.opacity = "0";
        setTimeout(() => {
            modal.close();
        }, 150);
    }

    // 3. Cerrar al hacer clic en el backdrop (fuera del área del modal)
    modales.forEach(modal => {
        modal.addEventListener("click", (event) => {
            if (event.clientX === 0 && event.clientY === 0) return; // Ignora eventos simulados

            const rect = modal.getBoundingClientRect();
            const clickAfuera = (
                event.clientX < rect.left ||
                event.clientX > rect.right ||
                event.clientY < rect.top ||
                event.clientY > rect.bottom
            );

            if (clickAfuera) {
                cerrarModalConAnimacion(modal);
            }
        });

        // Cierre al presionar ESC
        modal.addEventListener("cancel", (e) => {    
            e.preventDefault();
            cerrarModalConAnimacion(modal);
        });
    });

    // 4. Botones con name="modalBotonCerrar"
    const botonesCerrar = document.querySelectorAll('[name="modalBotonCerrar"]');
    botonesCerrar.forEach(cerrar => {
        cerrar.addEventListener("click", () => {
            const modalId = cerrar.dataset.modal;
            const modal = document.getElementById(modalId);
            cerrarModalConAnimacion(modal);
        });
    });
});