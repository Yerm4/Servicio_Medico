let section1Box = document.getElementById("section-1-box")
let consulta = document.getElementById("consulta")
let modales = document.querySelectorAll(".modal-crud")

function desFocus () {
    usuario.classList.remove("focus")
    consulta.classList.remove("focus")
    sesion.classList.remove("focus")
}

consulta.addEventListener("click", (e) => {
    consulta.style.pointerEvents = "none"
    desFocus()
    consulta.classList.add("focus")
    section1Box.style.opacity = 0

        const btnIniciar = document.createElement('a');
        btnIniciar.name = 'openModal';
        btnIniciar.setAttribute('data-modal', 'modalRegistrarConsulta');
        btnIniciar.className = 'action-card__button action-card__button--grid-principal';
        btnIniciar.href = '#';
        btnIniciar.textContent = 'Iniciar consulta';

        const btnActualizar = document.createElement('a');
        btnActualizar.name = 'openModal';
        btnActualizar.setAttribute('data-modal', 'modalActualizarConsulta');
        btnActualizar.className = 'action-card__button';
        btnActualizar.href = '#';
        btnActualizar.textContent = 'Actualiza consulta';

        const btnBuscar = document.createElement('a');
        btnBuscar.name = 'openModal';
        btnBuscar.setAttribute('data-modal', 'modalBuscarConsulta');
        btnBuscar.className = 'action-card__button';
        btnBuscar.href = '#';
        btnBuscar.textContent = 'Buscar consulta';

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(btnIniciar)
        section1Box.appendChild(btnBuscar)
        section1Box.appendChild(btnActualizar)
        section1Box.style.opacity = "1"
        consulta.style.pointerEvents = "auto"
    }, 500);
})

let usuario = document.getElementById("usuario")
usuario.addEventListener("click", (e) => {
    usuario.style.pointerEvents = "none"
    desFocus()
    usuario.classList.add("focus")
    section1Box.style.opacity = 0

        const btnRegistrar = document.createElement('a');
        btnRegistrar.name = 'openModal';
        btnRegistrar.setAttribute('data-modal', 'modalRegistrarUsuario');
        btnRegistrar.className = 'action-card__button';
        btnRegistrar.href = '#';
        btnRegistrar.textContent = 'Registrar usuario';

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(btnRegistrar)
        section1Box.style.opacity = "1"
        usuario.style.pointerEvents = "auto"
    }, 500);

})

let sesion = document.getElementById("sesion")
sesion.addEventListener("click", (e) => {
    sesion.style.pointerEvents = "none"
    desFocus()
    sesion.classList.add("focus")
    section1Box.style.opacity = 0

    const btnLogout = document.createElement('a');
    btnLogout.href = 'logout';
    btnLogout.style.color = 'blue';
    btnLogout.textContent = 'Cerrar sesión';

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(btnLogout)
        section1Box.style.opacity = "1"
        sesion.style.pointerEvents = "auto"
    }, 500);
})

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