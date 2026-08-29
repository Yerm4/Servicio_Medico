let section1Box = document.getElementById("section-1-box")
let consulta = document.getElementById("consulta")
let usuario = document.getElementById("usuario")
let sesion = document.getElementById("sesion")
let configuracionLink = document.getElementById("configuracion")
let modales = document.querySelectorAll(".modal-crud")

let tablaRegistros = document.getElementById("tablaRegistros")
let buscadorCaja = document.querySelector(".buscador-caja")
let seccionConfiguracion = document.getElementById("seccion-configuracion")

function desFocus () {
    if (usuario) usuario.classList.remove("focus")
    if (consulta) consulta.classList.remove("focus")
    if (sesion) sesion.classList.remove("focus")
    if (configuracionLink) configuracionLink.classList.remove("focus")
}

function ocultarTodo() {
    if (tablaRegistros) tablaRegistros.style.display = "none"
    if (seccionConfiguracion) seccionConfiguracion.style.display = "none"
}

let subTabGeneral = document.getElementById("sub-tab-general");
let subTabRoles = document.getElementById("sub-tab-roles");
let subTabCondiciones = document.getElementById("sub-tab-condiciones");
let subContentGeneral = document.getElementById("sub-content-general");
let subContentRoles = document.getElementById("sub-content-roles");
let subContentCondiciones = document.getElementById("sub-content-condiciones");

function activarTab(tabActivo, contentActivo) {
    [subTabGeneral, subTabRoles, subTabCondiciones].forEach(tab => {
        if(tab) {
            tab.style.color = (tab === tabActivo) ? "#333" : "#777";
            tab.style.borderBottom = (tab === tabActivo) ? "3px solid blue" : "3px solid transparent";
        }
    });
    [subContentGeneral, subContentRoles, subContentCondiciones].forEach(content => {
        if(content) content.style.display = (content === contentActivo) ? "block" : "none";
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
const loginCardCedula = document.querySelectorAll("input[name=cedula]")

const inputNombre = document.querySelectorAll("input[name=nombre], input[name=apellido], input[name=nombre_contacto_emergencia]")
inputNombre.forEach(input => {
    input.addEventListener('input', (e) => {
        const target = e.target;
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
        
        if (target.value.length > 30) {
            target.value = target.value.slice(0, 30);
        }
    });
});



const inputFecha = document.querySelectorAll('input[name=fecha_nacimiento]');

if (inputFecha.length > 0) {
    const hoy = new Date();
    const hoyFormateada = hoy.toLocaleDateString('sv-SE');

    const añoMinimo = hoy.getFullYear() - 110;
    const fechaMinFormateada = `${añoMinimo}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;

    inputFecha.forEach(input => {
        input.max = hoyFormateada;
        input.min = fechaMinFormateada;
    });
}


if (loginCardCedula) {
    loginCardCedula.forEach(inputCedula => {
        inputCedula.addEventListener("input", (event) => {
            const cedulaValue = event.target.value
            if (cedulaValue.length < 7 || cedulaValue.length > 8) {
                inputCedula.style.border = "2px red solid"
            } else {
                inputCedula.style.border = "2px green solid"
            }
        })
    });    
}

document.addEventListener("click", (event) => {
    const boton = event.target.closest('[data-modal]');
    
    if (boton) {
        event.preventDefault();
        let modalId = boton.dataset.modal;
        let modalAbrir = document.getElementById(modalId);
        
        if (modalAbrir) {
            try {
                modalAbrir.showModal();
                modalAbrir.style.opacity = 1;
            } catch (e) {
                console.error("Error opening modal:", e);
            }
        }
    }
});

modales.forEach(modal => {
    modal.addEventListener("click", (event) => {
        if (event.clientX === 0 && event.clientY === 0) {
            return; 
        }
        const modalPosicion = modal.getBoundingClientRect()
        const clickAfuera = (
            event.clientX < modalPosicion.left ||
            event.clientX > modalPosicion.right ||
            event.clientY < modalPosicion.top ||
            event.clientY > modalPosicion.bottom 
        )
        if (clickAfuera) {
            modal.style.opacity = 0
            setTimeout(() => {
            modal.close()
            }, 150);
        }
    })
})

const modalBotonCerrar = document.querySelectorAll('[name="modalBotonCerrar"]')
modalBotonCerrar.forEach(cerrar => {
    cerrar.addEventListener("click", (event) => {
        const modalId = cerrar.dataset.modal
        let modal = document.getElementById(modalId)
        modal.style.opacity = 0
        setTimeout(() => {
            modal.close()
        }, 150);
        
    })
})

modales.forEach(modal => {
    modal.addEventListener("cancel", (e) => {    
        e.preventDefault()
        modal.style.opacity = 0
        setTimeout(() => {
            modal.close()
        }, 150);
    })
})

document.addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("editar-rol")) {
        const idRol = e.target.getAttribute("data-id");
        const nombreRol = e.target.getAttribute("data-nombre");
        const descripcionRol = e.target.getAttribute("data-descripcion");
        
        const inputId = document.getElementById("edit_id_rol");
        const inputNombre = document.getElementById("edit_nombre_rol");
        const inputDesc = document.getElementById("edit_descripcion_rol");
        const modal = document.getElementById("modalEditarRol");
        
        if (inputId && inputNombre && inputDesc && modal) {
            inputId.value = idRol;
            inputNombre.value = nombreRol;
            inputDesc.value = descripcionRol;
            modal.showModal();
            modal.style.opacity = 1;
        }
    }
});
