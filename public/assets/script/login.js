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

const loginCardCedula = document.querySelectorAll("input[name=cedula]")

if (loginCardCedula) {
    loginCardCedula.forEach(input => {
    input.addEventListener('input', function() {
        
        this.value = this.value.replace(/\D/g, '');
        
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
});
}

const telefonos = document.querySelectorAll("input[name=tlfprincipal], input[name=tlfemergencia]")

if (telefonos) {
    telefonos.forEach(input => {
    input.addEventListener('input', function() {
        
        this.value = this.value.replace(/\D/g, '');
        
        if (this.value.length > 20) {
            this.value = this.value.slice(0, 20);
        }
    });
});
}

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

function cargarPnfsPorNucleo(idNucleo, selectPnfElement, pnfSeleccionado = null) {
    if (!selectPnfElement) return;

    selectPnfElement.innerHTML = '<option value="">No aplica / Seleccione...</option>';
    selectPnfElement.disabled = true;

    if (!idNucleo || idNucleo === "") {
        return;
    }

    const tokenCSRF = document.querySelector('input[name="csrf_token"]').value;

    fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `csrf_token=${tokenCSRF}&form=obtener_pnfs_por_nucleo&id_nucleo=${idNucleo}`
    })
    .then(response => response.json())
    .then(pnfs => {
        if (Array.isArray(pnfs) && pnfs.length > 0) {
            selectPnfElement.disabled = false;
            pnfs.forEach(pnf => {
                const opt = document.createElement('option');
                opt.value = pnf.id_pnf;
                opt.textContent = pnf.nombre_pnf;
                if (pnfSeleccionado && String(pnf.id_pnf) === String(pnfSeleccionado)) {
                    opt.selected = true;
                }
                selectPnfElement.appendChild(opt);
            });
        }
    })
    .catch(error => console.error("Error al cargar PNFs:", error));
}

const selectNucleoReg = document.getElementById('nucleo_id');
const selectPnfReg = document.getElementById('pnf_id');
if (selectNucleoReg && selectPnfReg) {
    selectNucleoReg.addEventListener('change', function() {
        cargarPnfsPorNucleo(this.value, selectPnfReg);
    });
}