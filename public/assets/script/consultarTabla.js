const inputBuscar = document.getElementById('inputBuscarUsuario');
const cuerpoTabla = document.getElementById('cuerpoTablaUsuarios');
const elModalActualizar = document.getElementById('modalActualizarUsuario');

// 💡 Función auxiliar para calcular la edad exacta en JS
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

// ====== BLOQUE 1: EL BUSCADOR ASÍNCRONO ======
if (inputBuscar && cuerpoTabla) {
    inputBuscar.addEventListener('input', function() {
        const textoBusqueda = inputBuscar.value.trim();
        const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name=\"csrf_token\"]').value;

        fetch('index.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `csrf_token=${tokenCSRF}&form=buscar_usuario&query=${encodeURIComponent(textoBusqueda)}`
        })
        .then(response => response.json())
        .then(usuarios => {
            cuerpoTabla.innerHTML = '';

            if (usuarios.length === 0) {
                cuerpoTabla.innerHTML = `<tr><td colspan="9" style="text-align:center;">No se encontraron usuarios.</td></tr>`;
                return;
            }

            usuarios.forEach(usuario => {
                const fila = document.createElement('tr');
                
                const tipoUsuario = usuario.tipo == 0 ? 'Estudiante' : 'Docente';
                const sexoUsuario = usuario.sexo == 1 ? 'Masculino' : 'Femenino';
                const edadCalculada = calcularEdadJS(usuario.fecha_nacimiento);

                fila.innerHTML = `
                    <td class="tabla-usuarios__desc">${usuario.cedula}</td>
                    <td class="tabla-usuarios__desc">${usuario.nombre || ''}</td>
                    <td class="tabla-usuarios__desc">${usuario.apellido || ''}</td>
                    <td class="tabla-usuarios__desc">${tipoUsuario}</td>
                    <td class="tabla-usuarios__desc">${edadCalculada}</td>
                    <td class="tabla-usuarios__desc">${sexoUsuario}</td>
                    <td class="tabla-usuarios__desc">${usuario.tlfprincipal || ''}</td>
                    <td class="tabla-usuarios__desc">
                        <button class="eliminar-usuario" data-id="${usuario.cedula}">Eliminar</button>
                    </td>
                    <td class="tabla-usuarios__desc">
                        <button class="editar-usuario" data-id="${usuario.cedula}">Actualizar</button>
                    </td>
                `;
                cuerpoTabla.appendChild(fila);
            });
        })
        .catch(error => console.error("Error al buscar:", error));
    });
}

// ====== BLOQUE 2: ESCUCHADOR CLIC PARA ABRIR EL MODAL ======
if (cuerpoTabla && elModalActualizar) {
    cuerpoTabla.addEventListener('click', function(event) {
        if (event.target.classList.contains('editar-usuario')) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name=\"csrf_token\"]').value;

            // Buscamos los datos del usuario en la base de datos
            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_usuario&id=${cedula}`
            })
            .then(response => response.json())
            // Busca la parte dentro de tu fetch('index.php') que rellena el formulario y déjala así:
.then(usuario => {
    if (usuario.error) {
        alert(usuario.error);
        return;
    }

    // Rellenamos exactamente los mismos campos que el registro
    document.getElementById('edit_cedula').value = usuario.cedula;
    document.getElementById('edit_nombre').value = usuario.nombre || '';
    document.getElementById('edit_apellido').value = usuario.apellido || '';
    document.getElementById('edit_tipo').value = usuario.tipo;
    document.getElementById('edit_fecha_nacimiento').value = usuario.fecha_nacimiento || '';
    document.getElementById('edit_tlfprincipal').value = usuario.tlfprincipal || '';
    document.getElementById('edit_nombre_contacto_emergencia').value = usuario.nombre_contacto_emergencia || '';
    document.getElementById('edit_tlfemergencia').value = usuario.tlfemergencia || '';
    document.getElementById('edit_direccion').value = usuario.direccion || '';

    // Manejo de los botones de selección de sexo
    if (usuario.sexo == 1) {
        document.getElementById('edit_sexo_m').checked = true;
    } else if (usuario.sexo == 2) {
        document.getElementById('edit_sexo_f').checked = true;
    }

    // Forzamos la apertura nativa de la modal
    elModalActualizar.showModal();

                setTimeout(() => {
                    elModalActualizar.style.opacity = "1"
                }, 500);
            })
            .catch(error => console.error("Error al cargar datos del usuario:", error));
        }
    });
}

// ====== BLOQUE 3: PROCESAR EL ENVÍO DE ACTUALIZACIÓN ======
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
                elModalActualizar.close();
                
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