const inputBuscar = document.getElementById('inputBuscarUsuario');
const cuerpoTabla = document.getElementById('cuerpoTablaUsuarios');
const modalActualizar = document.getElementById('modalActualizarUsuario');
const modalDetallesUsuario = document.getElementById('modalDetallesUsuario');

function calcularEdadJS(fechaNacimiento) {
    if (!fechaNacimiento || fechaNacimiento.trim() === "") return "No registrado";
    
    const nacimiento = new Date(fechaNacimiento);
    const actual = new Date();
    
    if (nacimiento > actual) return "Fecha invalida";
    
    let edad = actual.getFullYear() - nacimiento.getFullYear();
    const mes = actual.getMonth() - nacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && actual.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}

if (inputBuscar && cuerpoTabla) {
    inputBuscar.addEventListener('input', async function() {
        
        const buscar = inputBuscar.value.trim();
        const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

        try {
            const response = await fetch(`api/users/buscar/${buscar || 0}`)
            const result = await response.json().catch(() => null)
    
            if (!response.ok) {
                const error = (result?.message ?? "") || response.status+": "+response.statusText
                throw new Error(error)
            }
                
            if (!result) {
                throw new Error("La respuesta no es JSON")
            }
    
            if (result.status === "ok") {
                cuerpoTabla.innerHTML = '';

                const usuarios = result.data
                if (usuarios.length === 0) {
                    cuerpoTabla.innerHTML = `<tr><td colspan="9" style="text-align:center;">No se encontraron usuarios.</td></tr>`;
                    return;
                }

                usuarios.forEach(usuario => {
                    const fila = document.createElement('tr');
    
                    const tdActualizar = document.createElement("td");
                    tdActualizar.className = "tabla-usuarios__desc";
    
                    const btnActualizar = document.createElement("button");
                    btnActualizar.className = "editar-usuario action-card__button";
                    btnActualizar.innerHTML = `
                    <svg style="pointer-events:none" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9087 3.87352C16.4681 3.31421 17.2266 3 18.0176 3C18.4093 3 18.7971 3.07714 19.1589 3.22702C19.5208 3.3769 19.8495 3.59658 20.1265 3.87352C20.4034 4.15046 20.6231 4.47924 20.773 4.84108C20.9229 5.20292 21 5.59074 21 5.98239C21 6.37404 20.9229 6.76186 20.773 7.1237C20.6231 7.48554 20.4034 7.81432 20.1265 8.09126L19.0231 9.19466C18.6326 9.58519 17.9994 9.58519 17.6089 9.19467L14.8053 6.39114C14.4148 6.00062 14.4148 5.36745 14.8053 4.97693L15.9087 3.87352ZM13.3911 7.80536C13.0006 7.41483 12.3674 7.41483 11.9769 7.80536L5.01084 14.7714C4.37004 15.4122 3.91545 16.2151 3.69566 17.0943L3.02986 19.7575C2.94467 20.0982 3.04452 20.4587 3.2929 20.7071C3.54128 20.9555 3.90177 21.0553 4.24254 20.9701L6.90572 20.3043C7.78488 20.0846 8.58778 19.63 9.22857 18.9892L16.1946 12.0231C16.5852 11.6326 16.5852 10.9994 16.1946 10.6089L13.3911 7.80536Z" fill="#000000"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C12 19.4477 12.4477 19 13 19L20 19C20.5523 19 21 19.4477 21 20C21 20.5523 20.5523 21 20 21L13 21C12.4477 21 12 20.5523 12 20Z" fill="#000000"/>
                    </svg>`
                    btnActualizar.dataset.id = usuario.cedula; 
    
                    tdActualizar.appendChild(btnActualizar);
    
                    const tdDetalles = document.createElement("td");
                    tdDetalles.className = "tabla-usuarios__desc";
    
                    const btnDetalles = document.createElement("button");
                    btnDetalles.className = "consultar-usuario action-card__button";
                    btnDetalles.innerHTML = `
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="13" r="2" stroke="#000000" stroke-linejoin="round"/>
                        <path d="M12 7.5C7.69517 7.5 4.47617 11.0833 3.39473 12.4653C3.14595 12.7832 3.14595 13.2168 3.39473 13.5347C4.47617 14.9167 7.69517 18.5 12 18.5C16.3048 18.5 19.5238 14.9167 20.6053 13.5347C20.8541 13.2168 20.8541 12.7832 20.6053 12.4653C19.5238 11.0833 16.3048 7.5 12 7.5Z" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    `
                    btnDetalles.dataset.id = usuario.cedula;
    
                    tdDetalles.appendChild(btnDetalles);
    
                    const tdEliminar = document.createElement("td");
                    tdEliminar.className = "tabla-usuarios__desc";
    
                    const btnEliminar = document.createElement("button");
                    btnEliminar.className = "eliminar-usuario action-card__button";
                    btnEliminar.innerHTML = `
                    <svg width="30" height="30" viewBox="0 0 512 512" fill="#000000" xmlns="http://www.w3.org/2000/svg">
                        <path d="M316.325 44.522V0H195.68l-.003 44.522H61.217v87.982h21.616c3.975 75.409 20.548 350.983 21.319 363.803L105.097 512h301.806l.944-15.694c.771-12.821 17.345-288.394 21.319-363.803h21.616V44.522H316.325zM229.069 33.391h53.866v11.13h-53.866V33.391zM375.458 478.609H136.542c-3.633-60.548-16.681-278.597-20.27-346.105h279.456c-3.588 67.503-16.637 285.551-20.27 346.105zM417.391 99.112H94.609V77.913h322.783v21.199z"/>
                        <path d="M239.304 167.947h33.391v280.031h-33.391z"/>
                        <path d="M160.292 168.19h33.39v279.952h-33.39z" transform="matrix(.9986 -.0521 .0521 .9986 -15.8157 9.64)"/>
                        <path d="M195.052 291.462h279.952v33.39H195.052z" transform="matrix(.0521 -.9986 .9986 .0521 9.8344 626.6741)"/>
                    </svg>
                    `;
                    btnEliminar.dataset.id = usuario.cedula;
    
                    tdEliminar.appendChild(btnEliminar);
                
                    function crearTd(valor) {
                        const td = document.createElement("td");
                        td.classList.add("tabla-usuarios__desc")
                        td.textContent = valor
                        return td;
                    }
                    
                    fila.append(
                        crearTd(usuario.cedula),
                        crearTd(usuario.nombre),
                        crearTd(usuario.apellido),
                        crearTd(usuario.nombre_tipo || usuario.tipo),
                        crearTd(calcularEdadJS(usuario.fecha_nacimiento)),
                        crearTd(usuario.tlfprincipal),
                        tdDetalles,
                        tdActualizar, 
                        tdEliminar
                    );
                    cuerpoTabla.appendChild(fila);
                })
            }
    
        } catch (error) {
            console.error(error)
        }
    });
}

if (cuerpoTabla && modalActualizar) {
    cuerpoTabla.addEventListener('click', async function(event) {

        const editarUsuario = event.target.classList.contains('editar-usuario')
        const consultarUsuario = event.target.classList.contains('consultar-usuario')
        const eliminarUsuario = event.target.classList.contains('eliminar-usuario')
        if (editarUsuario) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

            try {
                const response = await fetch(`api/users/buscar/${cedula}`)
                const result = await response.json().catch(() => null)
        
                if (!response.ok) {
                    const error = (result?.message ?? "") || response.status+": "+response.statusText
                    throw new Error(error)
                }
                    
                if (!result) {
                    throw new Error("La respuesta no es JSON")
                }
                
                if (result.status === "ok") {
                    const usuario = result.data[0]
                    document.getElementById('edit_cedula').value = usuario.cedula;
                    document.getElementById('edit_nombre').value = usuario.nombre || '';
                    document.getElementById('edit_apellido').value = usuario.apellido || '';
                    document.getElementById('edit_tipo').value = usuario.tipo;
                    document.getElementById('edit_fecha_nacimiento').value = usuario.fecha_nacimiento || '';
                    document.getElementById('edit_tlfprincipal').value = usuario.tlfprincipal || '';
                    document.getElementById('edit_nombre_contacto_emergencia').value = usuario.nombre_contacto_emergencia || '';
                    document.getElementById('edit_tlfemergencia').value = usuario.tlfemergencia || '';
                    document.getElementById('edit_direccion').value = usuario.direccion || '';

                    const editRolField = document.getElementById('edit_rol');
                    if (editRolField && usuario.rol !== undefined) {
                        editRolField.value = usuario.rol;
                    }
                    console.log(usuario)
                    const editNucleoField = document.getElementById('edit_nucleo');
                    const editPnfField = document.getElementById('edit_pnf');
                    if (editNucleoField && editPnfField) {
                        editNucleoField.value = usuario.nucleo_id || '';
                        cargarPnfsPorNucleo(usuario.nucleo_id, editPnfField, usuario.pnf_id);
                    }

                    if (usuario.sexo === 1) {
                        document.getElementById('edit_sexo_m').checked = true;
                    } else if (usuario.sexo === 2) {
                        document.getElementById('edit_sexo_f').checked = true;
                    }

                    modalActualizar.showModal();

                    setTimeout(() => {
                        modalActualizar.style.opacity = "1"
                    }, 500);
                }
                
        
            } catch (error) {
                console.error(error)
            }
            return
        }

        if (consultarUsuario) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

            try {
                const response = await fetch(`api/users/buscar/${cedula}`)
                const result = await response.json().catch(() => null)
        
                if (!response.ok) {
                    const error = (result?.message ?? "") || response.status+": "+response.statusText
                    throw new Error(error)
                }
                    
                if (!result) {
                    throw new Error("La respuesta no es JSON")
                }
            
                if (result.status === "ok") {
                    const usuario = result.data
                    const detalles = document.getElementById('detallesUsuario')
                    detalles.innerHTML = ""

                    function crearP(titulo, valor) {
                        const p = document.createElement("p");
                        p.textContent = `${titulo}${valor}`;
                        return p;
                    }
                    const contenido = document.createDocumentFragment()

                    contenido.append(
                        crearP("Cédula: ", usuario.cedula),
                        crearP("Nombre: ", usuario.nombre),
                        crearP("Apellido: ", usuario.apellido),
                        crearP("Tipo: ", usuario.nombre_tipo || usuario.tipo),
                        crearP("Fecha de Nacimiento: ", usuario.fecha_nacimiento),
                        crearP("Edad: ", calcularEdadJS(usuario.fecha_nacimiento)),
                        crearP("Teléfono Principal: ", usuario.tlfprincipal),
                        crearP("Teléfono Emergencia: ", usuario.tlfemergencia),
                        crearP("Contacto de Emergencia: ", usuario.nombre_contacto_emergencia),
                        crearP("Dirección: ", usuario.direccion),
                        crearP("Sexo: ", usuario.sexo == 1 ? "Masculino" : "Femenino"),
                        crearP("Núcleo: ", usuario.nombre_nucleo || "No asignado"),
                        crearP("PNF: ", usuario.nombre_pnf || "No asignado"),
                    );

                    detalles.append(contenido)

                    const editRolField = document.getElementById('edit_rol');
                    if (editRolField && usuario.rol !== undefined) {
                        editRolField.value = usuario.rol;
                    }

                    if (usuario.sexo == 1) {
                        document.getElementById('edit_sexo_m').checked = true;
                    } else if (usuario.sexo == 2) {
                        document.getElementById('edit_sexo_f').checked = true;
                    }

                    modalDetallesUsuario.showModal();

                    setTimeout(() => {
                        modalDetallesUsuario.style.opacity = "1"
                    }, 500);
                }
            } catch (error) {
                console.error(error)
            }
            return
        }

        if (eliminarUsuario) {
            event.preventDefault(); 
            const cedulaUsuario = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;
            
            if (confirm(`¿Segura de eliminar al usuario con cédula ${cedulaUsuario}?`)) {

                try {
                    const response = await fetch('api/users', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            "cedula": cedulaUsuario
                        })
                    })

                    const result = await response.json().catch(() => null)
            
                    if (!response.ok) {
                        const error = (result?.message ?? "") || response.status+": "+response.statusText
                        throw new Error(error)
                    }
                        
                    if (!result) {
                        throw new Error("La respuesta no es JSON")
                    }
                
                    if (result.status === "ok") {
                        event.target.closest('tr').remove();
                    } else {
                        alert("Error al eliminar: " + result.message);
                    }
                } catch (error) {
                    console.error(error)
                }
            } else return
            
            return
        }

    })
}

const formActualizar = document.getElementById('formActualizarUsuario');
if (formActualizar) {
    formActualizar.addEventListener('submit', function(event) {
        event.preventDefault();

        const datosEnvio = new URLSearchParams(new FormData(formActualizar)).toString();

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datosEnvio
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("¡Datos del usuario actualizados correctamente!");
                modalActualizar.close();
                
                if (inputBuscar) {
                    inputBuscar.dispatchEvent(new Event('input'));
                } else {
                    location.reload();
                }
            } else {
                alert("Error al actualizar: " + data.message);
            }
        })
        .catch(error => console.error("Error al procesar actualización:", error));
    });
}

async function cargarPnfsPorNucleo(idNucleo, selectPnfElement, pnfSeleccionado = null) {
    
    if (!selectPnfElement) return;

    selectPnfElement.innerHTML = '<option value="">No aplica / Seleccione...</option>';
    selectPnfElement.disabled = true;

    if (!idNucleo || idNucleo === "") return;
    
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