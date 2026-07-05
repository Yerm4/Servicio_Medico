document.addEventListener('DOMContentLoaded', function() {
    
    // =========================================================================
    // FUNCIÓN PARA REFRESCAR LA TABLA DESDE EL SERVIDOR (TRAE LOS IDS REALES)
    // =========================================================================
    function actualizarTablaCompleta() {
        // Buscamos la URL actual (sedes-carreras) para leer la tabla actualizada
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extraemos la tabla nueva renderizada por PHP
                const nuevaTabla = doc.getElementById('contenedor-tabla-dinamica');
                const contenedorActual = document.getElementById('contenedor-tabla-dinamica');
                
                if (nuevaTabla && contenedorActual) {
                    // Reemplazamos la vieja tabla por la nueva con su ID real de Base de Datos
                    contenedorActual.innerHTML = nuevaTabla.innerHTML;
                }
            })
            .catch(err => console.error("Error al refrescar la tabla:", err));
    }

    // =========================================================================
    // 1. CONTROL DE APERTURA DE MODAL "REGISTRAR PNF"
    // =========================================================================
    document.addEventListener('click', function(e) {
        const btnReg = e.target.closest('[data-modal="modalRegistrarPNF"]');
        if (btnReg) {
            e.preventDefault();
            const modalReg = document.getElementById('modalRegistrarPNF');
            if (modalReg) {
                modalReg.showModal(); 
                setTimeout(() => { modalReg.style.opacity = '1'; }, 50);
            }
        }
    });

    // =========================================================================
    // 2. ESCUCHAR EL BOTÓN "ACTUALIZAR" EN LA TABLA Y RELLENAR MODAL
    // =========================================================================
    document.addEventListener('click', function(event) {
        const boton = event.target.closest('.editar-pnf');
        if (boton) {
            event.preventDefault();
            const idPnf = boton.getAttribute('data-id');
            const nombrePnf = boton.getAttribute('data-nombre');
            const modalEditar = document.getElementById('modalActualizarPNF');
            
            if (modalEditar) {
                document.getElementById('edit_id_pnf').value = idPnf;
                document.getElementById('edit_nombre_pnf').value = nombrePnf || '';
                modalEditar.setAttribute('data-fila-id', idPnf);
                
                modalEditar.showModal();
                setTimeout(() => { modalEditar.style.opacity = '1'; }, 50);
            }
        }
    });

    // =========================================================================
    // 3. ENVIAR EL FORMULARIO DE REGISTRO (NUEVO PNF)
    // =========================================================================
    const formRegistrarPNF = document.getElementById('formRegistrarPNF');

    if (formRegistrarPNF) {
        formRegistrarPNF.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(this);
            const modalReg = document.getElementById('modalRegistrarPNF');

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. Limpiamos el formulario y cerramos la ventana
                    formRegistrarPNF.reset();
                    if (modalReg) modalReg.close();

                    // 2. Refrescamos la tabla directo desde el servidor para traer el ID real
                    actualizarTablaCompleta();
                    
                    // 3. Pintamos la alerta de éxito en su contenedor
                    setTimeout(() => {
                        mostrarAlertaSeccion("¡PNF registrado con éxito!", "success");
                    }, 300);

                } else {
                    if (modalReg) modalReg.close();
                    mostrarAlertaSeccion(data.message || "Ocurrió un error.", "error");
                }
            })
            .catch(error => {
                console.error("Error en el registro:", error);
                if (modalReg) modalReg.close();
                mostrarAlertaSeccion("Ocurrió un error al procesar el registro.", "error");
            });
        });
    }

    // =========================================================================
    // 4. ENVIAR EL FORMULARIO DE ACTUALIZACIÓN
    // =========================================================================
    const formActualizarPNF = document.getElementById('formActualizarPNF');

    if (formActualizarPNF) {
        formActualizarPNF.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const formData = new FormData(this);
            const modalEditar = document.getElementById('modalActualizarPNF');

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (modalEditar) modalEditar.close();
                    
                    // Refrescamos la tabla completa para actualizar nombres y atributos data
                    actualizarTablaCompleta();

                    setTimeout(() => {
                        mostrarAlertaSeccion("¡PNF actualizado con éxito!", "success");
                    }, 300);

                } else {
                    if (modalEditar) modalEditar.close();
                    mostrarAlertaSeccion(data.message || "Ocurrió un error.", "error");
                }
            })
            .catch(error => {
                console.error("Error en la actualización:", error);
                if (modalEditar) modalEditar.close();
                mostrarAlertaSeccion("Ocurrió un error al procesar la actualización.", "error");
            });
        });
    }

    // =========================================================================
    // 5. LIMPIAR OPACIDAD AL CERRAR MODALES
    // =========================================================================
    document.querySelectorAll('dialog').forEach(modal => {
        modal.addEventListener('close', () => {
            modal.style.opacity = '0';
        });
    });

    // =========================================================================
    // FUNCIÓN PARA PINTAR LAS NOTIFICACIONES
    // =========================================================================
    function mostrarAlertaSeccion(mensaje, tipo) {
        const contenedor = document.getElementById('alert-container-pnf');
        if (!contenedor) return;
        
        contenedor.innerHTML = `
            <div class="action-card" style="padding: 1rem; border-left: 5px solid ${tipo === 'success' ? '#2ecc71' : '#e74c3c'}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;">
                <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <span>${mensaje}</span>
                    <span style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;" onclick="this.parentElement.parentElement.parentElement.innerHTML = ''">×</span>
                </p>
            </div>
        `;

        setTimeout(() => {
            if (contenedor.firstChild) {
                contenedor.innerHTML = '';
            }
        }, 5000);
    }
    // INTERCEPTAR EL FORMULARIO DE ELIMINACIÓN CON FETCH
    // =========================================================================
    document.addEventListener('submit', function(e) {
        const formEliminar = e.target.closest('.form-eliminar-pnf');
        
        if (formEliminar) {
            e.preventDefault(); // Evitamos redirigir a la pantalla negra del JSON

            // Confirmación antes de proceder
            if (!confirm('¿Seguro que deseas eliminar este PNF?')) {
                return;
            }

            const formData = new FormData(formEliminar);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    actualizarTablaCompleta();

                    setTimeout(() => {
                        mostrarAlertaSeccion(data.message || "¡PNF eliminado con éxito!", "success");
                    }, 300);
                } else {
                    mostrarAlertaSeccion(data.message || "Ocurrió un error al intentar eliminar.", "error");
                }
            })
            .catch(error => {
                console.error("Error en la eliminación:", error);
                mostrarAlertaSeccion("Ocurrió un error al procesar la eliminación.", "error");
            });
        }
    });
});

// =========================================================================
// 1. REFRESCAR TABLA DE NÚCLEOS
// =========================================================================
function actualizarTablaNucleos() {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Reemplaza el cuerpo interno de la tabla de núcleos
            const nuevaTabla = doc.getElementById('cuerpoTablaNucleos');
            const contenedorActual = document.getElementById('cuerpoTablaNucleos');
            
            if (nuevaTabla && contenedorActual) {
                contenedorActual.innerHTML = nuevaTabla.innerHTML;
            }
        })
        .catch(err => console.error("Error al refrescar la tabla de núcleos:", err));
}

// =========================================================================
// 2. APERTURA DE MODAL "REGISTRAR NÚCLEO"
// =========================================================================
document.addEventListener('click', function(e) {
    const btnRegNucleo = e.target.closest('[data-modal="modalRegistrarNucleo"]');
    if (btnRegNucleo) {
        e.preventDefault();
        const modal = document.getElementById('modalRegistrarNucleo');
        if (modal) { 
            modal.showModal(); 
            setTimeout(() => { modal.style.opacity = '1'; }, 50); 
        }
    }
});

// =========================================================================
// 3. BOTÓN "ACTUALIZAR" (RELLENAR MODAL EDICIÓN NÚCLEO)
// =========================================================================
document.addEventListener('click', function(event) {
    const botonNucleo = event.target.closest('.editar-nucleo');
    if (botonNucleo) {
        event.preventDefault();
        const idNucleo = botonNucleo.getAttribute('data-id');
        const nombreNucleo = botonNucleo.getAttribute('data-nombre');
        const modalEditarNucleo = document.getElementById('modalActualizarNucleo');
        
        if (modalEditarNucleo) {
            document.getElementById('edit_id_nucleo').value = idNucleo;
            document.getElementById('edit_nombre_nucleo').value = nombreNucleo || '';
            modalEditarNucleo.showModal();
            setTimeout(() => { modalEditarNucleo.style.opacity = '1'; }, 50);
        }
    }
});

// =========================================================================
// 4. PROCESAR REGISTRO Y ACTUALIZACIÓN DE NÚCLEOS (MÉTODO REUTILIZABLE)
// =========================================================================
const configurarEnvioFormularioNucleo = (idForm, idModal, msgExitoDefault) => {
    const form = document.getElementById(idForm);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const modal = document.getElementById(idModal);

        fetch('index.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                form.reset();
                if (modal) modal.close();
                actualizarTablaNucleos(); // Refrescamos
                setTimeout(() => {
                    mostrarAlertaNucleo(data.message || msgExitoDefault, "success");
                }, 300);
            } else {
                if (modal) modal.close();
                mostrarAlertaNucleo(data.message || "Ocurrió un error.", "error");
            }
        })
        .catch(error => {
            console.error(`Error en formulario ${idForm}:`, error);
            if (modal) modal.close();
            mostrarAlertaNucleo("Ocurrió un error al procesar la solicitud.", "error");
        });
    });
};

// Inicializamos los dos formularios del módulo
configurarEnvioFormularioNucleo('formRegistrarNucleo', 'modalRegistrarNucleo', "¡Núcleo registrado con éxito!");
configurarEnvioFormularioNucleo('formActualizarNucleo', 'modalActualizarNucleo', "¡Núcleo actualizado con éxito!");

// =========================================================================
// 5. INTERCEPTAR ELIMINACIÓN DE NÚCLEOS
// =========================================================================
document.addEventListener('submit', function(e) {
    const formEliminarNucleo = e.target.closest('.form-eliminar-nucleo');
    if (formEliminarNucleo) {
        e.preventDefault();
        if (!confirm('¿Seguro que deseas eliminar este núcleo?')) return;

        fetch('index.php', { method: 'POST', body: new FormData(formEliminarNucleo) })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                actualizarTablaNucleos();
                setTimeout(() => { mostrarAlertaNucleo(data.message || "¡Núcleo eliminado!", "success"); }, 300);
            } else {
                mostrarAlertaNucleo(data.message || "Error al eliminar.", "error");
            }
        }).catch(err => console.error(err));
    }
});

// =========================================================================
// 6. ALERTAS EXCLUSIVAS PARA NÚCLEOS
// =========================================================================
function mostrarAlertaNucleo(mensaje, tipo) {
    const contenedor = document.getElementById('alert-container-nucleo');
    if (!contenedor) return;
    
    contenedor.innerHTML = `
        <div class="action-card" style="padding: 1rem; border-left: 5px solid ${tipo === 'success' ? '#2ecc71' : '#e74c3c'}; background: #fdfdfd; width: 100%; box-sizing: border-box; margin-bottom: 1rem;">
            <p style="margin: 0; font-weight: bold; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <span>${mensaje}</span>
                <span style="cursor: pointer; font-size: 1.2rem; padding: 0 5px;" onclick="this.parentElement.parentElement.parentElement.innerHTML = ''">×</span>
            </p>
        </div>
    `;

    setTimeout(() => {
        if (contenedor.firstChild) { contenedor.innerHTML = ''; }
    }, 5000);
}
