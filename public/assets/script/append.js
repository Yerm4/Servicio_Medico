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
let subContentGeneral = document.getElementById("sub-content-general");
let subContentRoles = document.getElementById("sub-content-roles");

if (subTabGeneral && subTabRoles && subContentGeneral && subContentRoles) {
    subTabGeneral.addEventListener("click", (e) => {
        e.preventDefault();
        subTabGeneral.style.color = "#333";
        subTabGeneral.style.borderBottom = "3px solid blue";
        subTabRoles.style.color = "#777";
        subTabRoles.style.borderBottom = "none";
        
        subContentGeneral.style.display = "block";
        subContentRoles.style.display = "none";
    });
    
    subTabRoles.addEventListener("click", (e) => {
        e.preventDefault();
        subTabRoles.style.color = "#333";
        subTabRoles.style.borderBottom = "3px solid blue";
        subTabGeneral.style.color = "#777";
        subTabGeneral.style.borderBottom = "none";
        
        subContentRoles.style.display = "block";
        subContentGeneral.style.display = "none";
    });
}

const loginCardCedula = document.getElementsByName("cedula")
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

section1Box.addEventListener("click", (event) => {
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
            }, 500);
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
        }, 500);
        
    })
})

modales.forEach(modal => {
    modal.addEventListener("cancel", (e) => {    
        e.preventDefault()
        modal.style.opacity = 0
        setTimeout(() => {
            modal.close()
        }, 500);
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