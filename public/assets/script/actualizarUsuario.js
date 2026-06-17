// ====== ACTO 1: ABRIR MODAL Y LLENAR SUS VALORES DESDE LA BD ======
const tablaUsuarios = document.getElementById('cuerpoTablaUsuarios');
const modalActualizar = document.getElementById('modalActualizarUsuario');

if (tablaUsuarios && modalActualizar) {
    tablaUsuarios.addEventListener('click', function(event) {
        if (event.target.classList.contains('editar-usuario')) {
            event.preventDefault();
            
            const cedula = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;

            // Buscamos los datos del usuario en la base de datos
            fetch('index.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${tokenCSRF}&form=obtener_usuario&id=${cedula}`
            })
            .then(response => response.json())
            .then(usuario => {
                if (usuario.error) {
                    alert(usuario.error);
                    return;
                }

                // Empezamos a rellenar campo por campo basándonos en los id de tu nuevo modal
                document.getElementById('edit_cedula').value = usuario.cedula;
                document.getElementById('edit_nombre').value = usuario.nombre || '';
                document.getElementById('edit_apellido').value = usuario.apellido || '';
                document.getElementById('edit_tipo').value = usuario.tipo;
                document.getElementById('edit_fecha_nacimiento').value = usuario.fecha_nacimiento || '';
                document.getElementById('edit_tlfprincipal').value = usuario.tlfprincipal || '';
                document.getElementById('edit_nombre_contacto_emergencia').value = usuario.nombre_contacto_emergencia || '';
                document.getElementById('edit_tlfemergencia').value = usuario.tlfemergencia || '';
                document.getElementById('edit_direccion').value = usuario.direccion || '';

                // Para activar el botón de radio correcto del Sexo (1 = Masculino, 2 = Femenino)
                if (parseInt(usuario.sexo) === 1) {
                    document.getElementById('edit_sexo_m').checked = true;
                } else if (parseInt(usuario.sexo) === 2) {
                    document.getElementById('edit_sexo_f').checked = true;
                }

                // Abrimos el modal de actualización
                modalActualizar.showModal();
            })
            .catch(error => console.error("Error al cargar datos del usuario:", error));
        }
    });
}

// ====== ACTO 2: GUARDAR LOS DATOS ACTUALIZADOS POR DETRÁS ======
const formActualizar = document.getElementById('formActualizarUsuario');
if (formActualizar) {
    formActualizar.addEventListener('submit', function(event) {
        event.preventDefault(); // Evitamos recarga completa de página

        // Empaquetamos automáticamente todos los datos del formulario de actualizar
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
                modalActualizar.close(); // Cerramos el modal
                
                // Refrescamos la tabla automáticamente simulando que escribiste algo en la barra
                document.getElementById('inputBuscarUsuario').dispatchEvent(new Event('input'));
            } else {
                alert("Error al actualizar: " + data.message);
            }
        })
        .catch(error => console.error("Error al procesar actualización:", error));
    });
}