document.addEventListener("DOMContentLoaded", () => {
    // Referencias a las sub-pestañas de Configuración
    const subTabGeneral = document.getElementById("sub-tab-general");
    const subTabRoles = document.getElementById("sub-tab-roles");
    const subTabCondiciones = document.getElementById("sub-tab-condiciones");
    const subTabOfertas = document.getElementById("sub-tab-ofertas");
    const subTabNucleos = document.getElementById("sub-tab-nucleos");
    const subTabPnf = document.getElementById("sub-tab-pnf");

    const subContentGeneral = document.getElementById("sub-content-general");
    const subContentRoles = document.getElementById("sub-content-roles");
    const subContentCondiciones = document.getElementById("sub-content-condiciones");
    const subContentOferta = document.getElementById("sub-content-oferta");
    const subContentNucleos = document.getElementById("sub-content-nucleos");
    const subContentPnf = document.getElementById("sub-content-pnf");

    function activarTab(tabActivo, contentActivo) {
        [subTabGeneral, subTabRoles, subTabCondiciones, subTabOfertas, subTabNucleos, subTabPnf].forEach(tab => {
            if (tab) {
                tab.style.color = (tab === tabActivo) ? "#333" : "#777";
                tab.style.borderBottom = (tab === tabActivo) ? "3px solid blue" : "3px solid transparent";
            }
        });

        [subContentGeneral, subContentRoles, subContentCondiciones, subContentOferta, subContentOferta, subContentNucleos, subContentPnf].forEach(content => {
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

    if (subTabOfertas) {
        subTabOfertas.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabOfertas, subContentOferta);
        });
    }
    if (subTabNucleos) {
        subTabNucleos.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabNucleos, subContentNucleos);
        });
    }
    if (subTabPnf) {
        subTabPnf.addEventListener("click", (e) => {
            e.preventDefault();
            activarTab(subTabPnf, subContentPnf);
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