const inputBuscar = document.getElementById('inputBuscarUsuario');
const cuerpoTabla = document.getElementById('cuerpoTablaUsuarios');

// 💡 Función auxiliar para calcular la edad exacta en JS basándonos en tu código de PHP
function calcularEdadJS(fechaNacimiento) {
    if (!fechaNacimiento) return "No registrado";
    
    const nacimiento = new Date(fechaNacimiento);
    const actual = new Date();
    
    if (nacimiento > actual) return "Fecha invalida";
    
    let edad = actual.getFullYear() - nacimiento.getFullYear();
    const mes = actual.getMonth() - nacimiento.getMonth();
    
    // Si aún no ha pasado el mes de cumpleaños, o es el mes pero no el día, restamos un año
    if (mes < 0 || (mes === 0 && actual.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}

if (inputBuscar && cuerpoTabla) {
    inputBuscar.addEventListener('input', function() {
        const textoBusqueda = inputBuscar.value.trim();
        
        // Buscamos el token CSRF dentro del formulario del modal
        const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name=\"csrf_token\"]').value;

        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `csrf_token=${tokenCSRF}&form=buscar_usuario&query=${encodeURIComponent(textoBusqueda)}`
        })
        .then(response => response.json())
        .then(usuarios => {
            // 1. Limpiamos las filas viejas
            cuerpoTabla.innerHTML = '';

            // 2. Si no hay resultados, mostramos el aviso ocupando las 8 columnas actuales
            if (usuarios.length === 0) {
                cuerpoTabla.innerHTML = `<tr><td colspan="8" style="text-align:center; padding: 15px;">No se encontraron usuarios.</td></tr>`;
                return;
            }

            // 3. Recorremos los usuarios y dibujamos la estructura completa idéntica a tu PHP
            usuarios.forEach(usuario => {
                const fila = document.createElement('tr');
                
                // Mapeamos los datos simulando exactamente las condicionales de tu vista PHP
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
                `;
                cuerpoTabla.appendChild(fila);
            });
        })
        .catch(error => console.error("Error al buscar:", error));
    });
}