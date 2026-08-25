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

const inputDireccion = document.querySelectorAll("input[name=direccion]")
inputDireccion.forEach(input => {
    input.addEventListener('input', (e) => {
        const target = e.target;
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
        
        if (target.value.length > 40) {
            target.value = target.value.slice(0, 40);
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

async function cargarPnfsPorNucleo(idNucleo, selectPnfElement, pnfSeleccionado = null) {
    
    if (!selectPnfElement) return;

    selectPnfElement.innerHTML = '<option value="">No aplica / Seleccione...</option>';
    selectPnfElement.disabled = true;

    if (!idNucleo || idNucleo === "") {
        return;
    }

    try {
        const response = await fetch(`api/nucleos/pnfs/${idNucleo}`)
        const result = await response.json().catch(() => null)

        if (!response.ok) {
            const error = (result?.message ?? "") || response.status+": "+response.statusText
            throw new Error(error)
        }
            
        if (!result) {
            throw new Error("La respuesta no es JSON")
        }

        if (result.status === "ok") {
            console.log(idNucleo)
            const data = result.data
            if (Array.isArray(data) && data.length > 0) {
                selectPnfElement.disabled = false;
                data.forEach(pnf => {
                const opt = document.createElement('option');
                opt.value = pnf.id_pnf;
                opt.textContent = pnf.nombre_pnf;
                if (pnfSeleccionado && String(pnf.id_pnf) === String(pnfSeleccionado)) {
                    opt.selected = true;
                }
                selectPnfElement.appendChild(opt);
            });
            }
        }

    } catch (error) {
        console.error(error)
    }
}

const selectNucleoReg = document.getElementById('nucleo_id');
const selectPnfReg = document.getElementById('pnf_id');
if (selectNucleoReg && selectPnfReg) {
    selectNucleoReg.addEventListener('change', function() {
        cargarPnfsPorNucleo(this.value, selectPnfReg);
    });
}

const loginForm = document.getElementById("loginForm")

if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
        e.preventDefault()
        const formData = new FormData(loginForm)
        const datos = Object.fromEntries(formData.entries())
    
        try {
            const response = await fetch("api/auth/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            
            const result = await response.json().catch(() => null)
            
            if (!response.ok) {
                const error = result?.message || response.status + ": " + response.statusText
                msg(loginMessage, error)
                throw new Error(error)
            }
            
            if (!result) throw new Error("La respuesta no fue JSON")
            
            if (result.status === "ok") {
                const data = result.data
                console.log(result.message)
                setTimeout(() => {
                    window.location.reload()
                }, 2000)
            } else {
                throw new Error (result.message)
            }
        } catch(error) {
            console.error(error)
        }
    })
}

const signupForm = document.getElementById("registroUsuarioForm") 

if (signupForm) {
    signupForm.addEventListener("submit", async (e) => {
        e.preventDefault()

        const formData = new FormData(signupForm)
        const datos = Object.fromEntries(formData.entries())

        try {
            const response = await fetch("api/users", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            
            const result = await response.json().catch(() => null)

            if (!response.ok) {
                const error = result?.message || response.status + ": " + response.statusText
                msg(signUpMessage, error)
                throw new Error(error)
            }

            if (!result) throw new Error("La respuesta no es JSON")

            if (result.status === "ok") {
                const data = result.data
                console.log(result.message)
                setTimeout(() => {
                    window.location.reload()
                }, 2000)
            }
        } catch (error) {
            console.error(error)
        }
    })
}