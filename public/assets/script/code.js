const loginCardCedula = document.getElementsByName("cedula")

if (loginCardCedula) {
    loginCardCedula.forEach(inputCedula => {
        inputCedula.addEventListener("input", (event) => {
            const cedulaValue = event.target.value
            if (cedulaValue.length < 7 || cedulaValue.length > 8) {
                inputCedula.style.border = "2px red solid"
            }
        
            else {
                inputCedula.style.border = "2px green solid"
            }
        })
    });    
}

const abrirModalBoton = document.querySelectorAll('[name="openModal"')
abrirModalBoton.forEach(boton => {
    boton.addEventListener("click", (event) => {
        event.preventDefault()
        let modal = boton.dataset.modal
        let modalAbrir = document.getElementById(modal)
        modalAbrir.showModal()
    })
})

const modales = document.querySelectorAll(".modal-crud")
modales.forEach(modal => {
    modal.addEventListener("click", (event) => {
        const modalPosicion = modal.getBoundingClientRect()
        const clickAfuera = (
            event.clientX < modalPosicion.left ||
            event.clientX > modalPosicion.right ||
            event.clientY < modalPosicion.top ||
            event.clientY > modalPosicion.bottom 
        )
        if (clickAfuera) {
            modal.close()
        }
    })
})

const modalBotonCerrar = document.querySelectorAll('[name="modalBotonCerrar"]')
modalBotonCerrar.forEach(cerrar => {
    cerrar.addEventListener("click", (event) => {
        const modal = cerrar.dataset.modal
        let modalCerrar = document.getElementById(modal)
        modalCerrar.close()
    })
})