let section1 = document.getElementById("section-1")
let consulta = document.getElementById("consulta")
let modales = document.querySelectorAll(".modal-crud")

// --- SECCIÓN: GENERACIÓN DE CONTENIDO DINÁMICO ---

consulta.addEventListener("click", (e) => {

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

    section1.style.opacity = "0"

    setTimeout(() => {
        section1.textContent = " "
        section1.appendChild(actionCard)
        section1.style.opacity = "1"
    }, 500);
    
    // ¡Ya no necesitas setTimeout ni re-buscar aquí!
})

let usuario = document.getElementById("usuario")
usuario.addEventListener("click", (e) => {
    section1.textContent = " "
    const actionCardUsuario = document.createElement('div');
    actionCardUsuario.className = 'action-card';

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

    actionCardUsuario.appendChild(cardTitle);
    actionCardUsuario.appendChild(buttonGrid);

    section1.appendChild(actionCardUsuario)
})

let sesion = document.getElementById("sesion")
sesion.addEventListener("click", (e) => {
    section1.textContent = ""
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

    section1.appendChild(actionCard)
})

// --- SECCIÓN: CONTROLADORES DE EVENTOS ---

// Validación de cédula (Mantenemos tu lógica igual)
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

/**
 * SOLUCIÓN AQUÍ: DELEGACIÓN DE EVENTOS PARA ABRIR MODALES
 * Escuchamos los clicks en 'section1'. Si el click vino de un botón con name="openModal",
 * actuamos de inmediato, sin importar en qué momento se creó ese botón.
 */
section1.addEventListener("click", (event) => {
    // Buscamos si el elemento clickeado (o su padre cercano) tiene el atributo name="openModal"
    const boton = event.target.closest('[name="openModal"]');
    
    if (boton) {
        event.preventDefault();
        let modalId = boton.dataset.modal;
        let modalAbrir = document.getElementById(modalId);
        if (modalAbrir) {
            modalAbrir.showModal();
        }
    }
});

// Cerrar modales haciendo click fuera (Mantenemos tu lógica igual ya que los modales suelen ser fijos)
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

// Cerrar modales con el botón interno (También por delegación si los botones están dentro del modal estático)
const modalBotonCerrar = document.querySelectorAll('[name="modalBotonCerrar"]')
modalBotonCerrar.forEach(cerrar => {
    cerrar.addEventListener("click", (event) => {
        const modal = cerrar.dataset.modal
        let modalCerrar = document.getElementById(modal)
        modalCerrar.close()
    })
})