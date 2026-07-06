const inputCedula = document.querySelectorAll("input[name=cedula]")
let modales = document.querySelectorAll(".modal-crud")

inputCedula.forEach(input => {
    input.addEventListener('input', function() {
        
        this.value = this.value.replace(/\D/g, '');
        
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
});


if (inputCedula) {
    inputCedula.forEach(inputCedula => {
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

const boton = document.querySelectorAll(".action-card__button")
    if (boton) {
        console.log("aaaa")
        boton.forEach(botonModal => {
            botonModal.addEventListener("click", (event) => {
            let modalId = botonModal.dataset.modal;
            let modalAbrir = document.getElementById(modalId);
            
            if (modalAbrir) {
                try {
                    modalAbrir.showModal();
                    modalAbrir.style.opacity = 1;
                } catch (e) {
                    console.error("Error opening modal:", e);
                }
            } 
                });
            })
    }

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