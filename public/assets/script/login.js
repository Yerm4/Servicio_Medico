const modales = document.querySelectorAll(".modal-crud")
const inputCedula = document.querySelectorAll("input[name=cedula]")
const loginCardCedula = document.querySelectorAll("input[name=cedula]")
const telefonos = document.querySelectorAll("input[name=tlfprincipal], input[name=tlfemergencia]")

const inputNombre = document.querySelectorAll("input[name=nombre], input[name=apellido], input[name=nombre_contacto_emergencia]")

const inputDireccion = document.querySelectorAll("input[name=direccion]")

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
            const pnfs = result.data
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
    const $inputCedula = $('#inputCedula');
    const $submitBtn = $('#loginForm button[type="submit"]');

    $inputCedula.on('blur', function() {
        const cedula = $(this).val().trim();
        let $cedulaAlert = $("#cedulaAlert");

        if (cedula.length === 0) {
            $inputCedula.css("border", "");
            $submitBtn.prop("disabled", true);
            $cedulaAlert.text("");
            $cedulaAlert.remove();
            return;
        }

        // Si tiene menos de 7 caracteres, no ha
        if (cedula.length < 7) {
            $submitBtn.prop("disabled", true);
            $cedulaAlert.text("");
            return;
        }

        // Si cumple con >= 7 caracteres, ejecutamos la petición AJAX
        $.ajax({
            url: `api/auth/${cedula}`,
            type: "GET",
            dataType: "json",
            success: function(result) {
                if ($cedulaAlert.length === 0) {
                    $cedulaAlert = $("<div>", {
                        id: "cedulaAlert",
                        css: { 
                            "font-size": "13px", 
                            "font-weight": "500", 
                            "opacity": "1"
                        }
                    });
                    $inputCedula.after($cedulaAlert); // Colocación exacta entre cédula y contraseña
                }

                if (result && result.status === "ok") {
                    // Éxito: Cédula encontrada
                    $inputCedula.css("border", "2px solid #166534"); // Borde verde
                    $submitBtn.prop("disabled", false);
                    $cedulaAlert.text("Cédula registrada").css("color", "#166534");
                } else {
                    // Error: Cédula no registrada
                    $inputCedula.css("border", "2px solid #991b1b"); // Borde rojo
                    $submitBtn.prop("disabled", true);
                    $cedulaAlert.text("Cédula no registrada.").css("color", "#991b1b");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al verificar la cédula:", error);
                $inputCedula.css("border", "2px solid #991b1b");
                if ($cedulaAlert.length === 0) {
                    $cedulaAlert = $("<div>", {
                        id: "cedulaAlert",
                        css: { 
                            "font-size": "13px", 
                            "font-weight": "500", 
                            "margin-top": "5px", 
                            "margin-bottom": "8px",
                            "color": "#991b1b"
                        }
                    });
                    $inputCedula.after($cedulaAlert);
                }
                $cedulaAlert.text("⚠️ Error al conectar con el servidor.");
                $submitBtn.prop("disabled", true);
            }
        });
    });

    $("#loginForm").on("submit", function(e) {
        e.preventDefault();

        const $submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = $submitBtn.text() || 'Ingresar al sistema';
        
        // Buscar o crear la caja de alertas para mensajes
        let $msgBox = $("#loginAlert");
        if ($msgBox.length === 0) {
            $msgBox = $("<div>", {
                id: "loginAlert",
                css: {
                    "margin-bottom": "12px", 
                    "padding": "10px", 
                    "border-radius": "6px", 
                    "font-size": "14px", 
                    "font-weight": "500", 
                    "text-align": "center"
                }
            });
            $(this).prepend($msgBox);
        }
        $msgBox.hide();

        // Convertir los datos del formulario a un objeto plano JSON
        const formDataArray = $(this).serializeArray();
        const datos = {};
        $.each(formDataArray, function(i, field) {
            datos[field.name] = field.value;
        });

        // Deshabilitar botón y mostrar estado de carga
        $submitBtn.prop("disabled", true).text("Ingresando...");

        // Petición AJAX con jQuery hacia tu API RESTful
        $.ajax({
            url: "api/auth/login",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(datos),
            dataType: "json",
            success: function(result) {
                if (result && result.status === "ok") {
                    $msgBox.text("¡Bienvenido! Redirigiendo...")
                        .css({
                            "background-color": "#dcfce7",
                            "color": "#166534",
                            "border": "1px solid #86efac"
                        })
                        .show();

                    const destino = result.redirect || "perfil";
                    setTimeout(function() {
                        window.location.href = destino;
                    }, 500);
                } else {
                    const errorMsg = result?.message || "Error al iniciar sesión";
                    $msgBox.text(errorMsg)
                        .css({
                            "background-color": "#fee2e2",
                            "color": "#991b1b",
                            "border": "1px solid #f87171"
                        })
                        .show();
                    $submitBtn.prop("disabled", false).text(originalBtnText);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en login:", error);
                let errorMsg = "Error al conectar con el servidor";
                try {
                    const resJson = JSON.parse(xhr.responseText);
                    if (resJson && resJson.message) {
                        errorMsg = resJson.message;
                    }
                } catch(e) {}

                $msgBox.text(errorMsg)
                    .css({
                        "background-color": "#fee2e2",
                        "color": "#991b1b",
                        "border": "1px solid #f87171"
                    })
                    .show();
                $submitBtn.prop("disabled", false).text(originalBtnText);
            }
        });
    });
    /* loginForm.addEventListener("submit", async (e) => {
        e.preventDefault()
        const submitBtn = loginForm.querySelector('button[type="submit"]')
        const originalBtnText = submitBtn ? submitBtn.textContent : 'Ingresar al sistema'
        
        let msgBox = document.getElementById("loginAlert")
        if (!msgBox) {
            msgBox = document.createElement("div")
            msgBox.id = "loginAlert"
            msgBox.style.cssText = "margin-bottom: 12px; padding: 10px; border-radius: 6px; font-size: 14px; font-weight: 500; text-align: center;"
            loginForm.prepend(msgBox)
        }
        msgBox.style.display = "none"

        const formData = new FormData(loginForm)
        const datos = Object.fromEntries(formData.entries())
    
        try {
            if (submitBtn) {
                submitBtn.disabled = true
                submitBtn.textContent = "Ingresando..."
            }

            const response = await fetch("api/auth/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            
            const result = await response.json().catch(() => null)
            
            if (!response.ok || !result || result.status !== "ok") {
                const error = result?.message || "Error"
                msgBox.textContent = error
                msgBox.style.backgroundColor = "#fee2e2"
                msgBox.style.color = "#991b1b"
                msgBox.style.border = "1px solid #f87171"
                msgBox.style.display = "block"
                if (submitBtn) {
                    submitBtn.disabled = false
                    submitBtn.textContent = originalBtnText
                }
                return
            }
            
            msgBox.textContent = "¡Bienvenido! Redirigiendo..."
            msgBox.style.backgroundColor = "#dcfce7"
            msgBox.style.color = "#166534"
            msgBox.style.border = "1px solid #86efac"
            msgBox.style.display = "block"

            const destino = result.redirect || "perfil"
            window.location.href = destino
        } catch(error) {
            console.error("Error en login:", error)
            msgBox.textContent = "Error al conectar con el servidor"
            msgBox.style.backgroundColor = "#fee2e2"
            msgBox.style.color = "#991b1b"
            msgBox.style.border = "1px solid #f87171"
            msgBox.style.display = "block"
            if (submitBtn) {
                submitBtn.disabled = false
                submitBtn.textContent = originalBtnText
            }
        }
    })
    */
}

const signupForm = document.getElementById("registroUsuarioForm") 

if (signupForm) {
    signupForm.addEventListener("submit", async (e) => {
        e.preventDefault()
        const submitBtn = signupForm.querySelector('button[type="submit"]')
        const originalBtnText = submitBtn ? submitBtn.textContent : 'Registrar'

        let alertBox = signupForm.querySelector(".signup-alert")
        if (!alertBox) {
            alertBox = document.createElement("div")
            alertBox.className = "signup-alert"
            alertBox.style.cssText = "margin-bottom: 12px; padding: 10px; border-radius: 6px; font-size: 14px; font-weight: 500; text-align: center;"
            signupForm.prepend(alertBox)
        }
        alertBox.style.display = "none"

        const formData = new FormData(signupForm)
        const datos = Object.fromEntries(formData.entries())

        try {
            if (submitBtn) {
                submitBtn.disabled = true
                submitBtn.textContent = "Registrando..."
            }

            const response = await fetch("api/users", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(datos)
            })
            
            const result = await response.json().catch(() => null)

            if (!response.ok || !result || result.status !== "ok") {
                const error = result?.message || (response.status + ": " + response.statusText)
                alertBox.textContent = error
                alertBox.style.backgroundColor = "#fee2e2"
                alertBox.style.color = "#991b1b"
                alertBox.style.border = "1px solid #f87171"
                alertBox.style.display = "block"
                if (submitBtn) {
                    submitBtn.disabled = false
                    submitBtn.textContent = originalBtnText
                }
                return
            }

            alertBox.textContent = result.message || "Usuario registrado con éxito"
            alertBox.style.backgroundColor = "#dcfce7"
            alertBox.style.color = "#166534"
            alertBox.style.border = "1px solid #86efac"
            alertBox.style.display = "block"

            setTimeout(() => {
                window.location.href = "perfil"
            }, 1000)
        } catch (error) {
            console.error("Error en registro:", error)
            alertBox.textContent = "Error al conectar con el servidor"
            alertBox.style.backgroundColor = "#fee2e2"
            alertBox.style.color = "#991b1b"
            alertBox.style.border = "1px solid #f87171"
            alertBox.style.display = "block"
            if (submitBtn) {
                submitBtn.disabled = false
                submitBtn.textContent = originalBtnText
            }
        }
    })
}