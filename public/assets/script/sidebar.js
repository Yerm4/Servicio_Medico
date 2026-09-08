document.addEventListener("DOMContentLoaded", () => {
    // Referencias a las sub-pestañas de Configuración
    const subTabGeneral = document.getElementById("sub-tab-general");
    const subTabRoles = document.getElementById("sub-tab-roles");
    const subTabCondiciones = document.getElementById("sub-tab-condiciones");

    const subContentGeneral = document.getElementById("sub-content-general");
    const subContentRoles = document.getElementById("sub-content-roles");
    const subContentCondiciones = document.getElementById("sub-content-condiciones");

    function activarTab(tabActivo, contentActivo) {
        [subTabGeneral, subTabRoles, subTabCondiciones].forEach(tab => {
            if (tab) {
                tab.style.color = (tab === tabActivo) ? "#333" : "#777";
                tab.style.borderBottom = (tab === tabActivo) ? "3px solid blue" : "3px solid transparent";
            }
        });

        [subContentGeneral, subContentRoles, subContentCondiciones].forEach(content => {
            if (content) {
                content.style.display = (content === contentActivo) ? "block" : "none";
            }
        });
    }

    if (subTabGeneral) {
        subTabGeneral.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabGeneral, subContentGeneral);
        });
    }

    if (subTabRoles) {
        subTabRoles.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabRoles, subContentRoles);
        });
    }

    if (subTabCondiciones) {
        subTabCondiciones.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabCondiciones, subContentCondiciones);
        });
    }
});

// Funciones auxiliares de visibilidad globales
function desFocus() {
    ["usuario", "consulta", "sesion", "configuracion"].forEach(id => {
        document.getElementById(id)?.classList.remove("focus");
    });
}

function ocultarTodo() {
    ["tablaRegistros", "seccion-configuracion"].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = "none";
    });
}