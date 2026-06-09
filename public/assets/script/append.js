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

        const actionCard = document.createElement('div');
        actionCard.className = 'action-card';

        const cardTitle = document.createElement('h2');
        cardTitle.className = 'action-card__title';
        cardTitle.textContent = 'Gestión de consultas';

        const buttonGrid = document.createElement('div');
        buttonGrid.className = 'action-card__button-grid';

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

        buttonGrid.appendChild(btnIniciar);
        buttonGrid.appendChild(btnActualizar);
        buttonGrid.appendChild(btnBuscar);
        actionCard.appendChild(cardTitle);
        actionCard.appendChild(buttonGrid);

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(actionCard)
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

        const actionCard = document.createElement('div');
        actionCard.className = 'action-card';

        const cardTitle = document.createElement('h2');
        cardTitle.className = 'action-card__title';
        cardTitle.textContent = 'Gestión de usuarios';

        const buttonGrid = document.createElement('div');
        buttonGrid.className = 'action-card__button-grid';

        const btnRegistrar = document.createElement('a');
        btnRegistrar.name = 'openModal';
        btnRegistrar.setAttribute('data-modal', 'modalRegistrarUsuario');
        btnRegistrar.className = 'action-card__button';
        btnRegistrar.href = '#';
        btnRegistrar.textContent = 'Registrar usuario';

        const btnActualizar = document.createElement('a');
        btnActualizar.name = 'openModal';
        btnActualizar.setAttribute('data-modal', 'modalActualizarUsuario');
        btnActualizar.className = 'action-card__button';
        btnActualizar.href = '#';
        btnActualizar.textContent = 'Actualizar usuario';

        const btnBuscar = document.createElement('a');
        btnBuscar.name = 'openModal';
        btnBuscar.setAttribute('data-modal', 'modalBuscarUsuario');
        btnBuscar.className = 'action-card__button';
        btnBuscar.href = '#';
        btnBuscar.textContent = 'Buscar usuario';

        const btnEliminar = document.createElement('a');
        btnEliminar.name = 'openModal';
        btnEliminar.setAttribute('data-modal', 'modalEliminarUsuario');
        btnEliminar.className = 'action-card__button';
        btnEliminar.href = '#';
        btnEliminar.textContent = 'Eliminar usuario';

        buttonGrid.appendChild(btnRegistrar);
        buttonGrid.appendChild(btnActualizar);
        buttonGrid.appendChild(btnBuscar);
        buttonGrid.appendChild(btnEliminar);

        actionCard.appendChild(cardTitle);
        actionCard.appendChild(buttonGrid);

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(actionCard)
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
    
    const actionCard = document.createElement('div');
    actionCard.className = 'action-card';

    const cardTitle = document.createElement('h2');
    cardTitle.textContent = 'Gestión de sesión';

    const btnLogout = document.createElement('a');
    btnLogout.href = 'logout';
    btnLogout.style.color = 'blue';
    btnLogout.textContent = 'Cerrar sesión';

    actionCard.appendChild(cardTitle);
    actionCard.appendChild(btnLogout);

    setTimeout(() => {
        section1Box.textContent = " "
        section1Box.appendChild(actionCard)
        section1Box.style.opacity = "1"
        sesion.style.pointerEvents = "auto"
    }, 500);
})

const loginCardCedula = document.getElementsByName("cedula")
if (loginCardCedula) {
    loginCardCedula.forEach(inputCedula => {
        inputCedula.addEventListener("input", (event) => {
            const cedulaValue = event.target.value
            if (cedulaValue.length < 7 || cedulaValue.length > 8) {
                inputCedula.style.border = "2px red solid"
            } else {
                inputCordula.style.border = "2px green solid"
            }
        })
    });    
}


section1Box.addEventListener("click", (event) => {

    const boton = event.target.closest('[name="openModal"]');
    
    if (boton) {
        event.preventDefault();
        let modalId = boton.dataset.modal;
        let modalAbrir = document.getElementById(modalId);
        
        if (modalAbrir) {
            modalAbrir.showModal();
            modalAbrir.style.opacity = 1
        }
    }
});

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