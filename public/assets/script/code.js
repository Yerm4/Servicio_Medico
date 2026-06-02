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

const abrirModalBoton = document.getElementsByName("openModal")

const modalRegistrarUsuario = document.getElementById("modalRegistrarUsuario")
const modalBuscarUsuario = document.getElementById("modalBuscarUsuario")

abrirModalBoton.forEach(boton => {
    boton.addEventListener("click", (event) => {
        event.preventDefault()
        let botonValue = boton.dataset.modal
        console.log(botonValue)
        let modalAbrir = document.getElementById(botonValue)
        modalAbrir.showModal()
    })
})