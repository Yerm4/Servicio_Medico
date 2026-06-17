const tablaUsuarios = document.getElementById("tablaRegistros")

if (tablaUsuarios) {
    tablaUsuarios.addEventListener('click', function(event) {
        
        if (event.target.classList.contains('eliminar-usuario')) {
            
            event.preventDefault(); 
            const cedulaUsuario = event.target.getAttribute('data-id');
            const tokenCSRF = document.querySelector('#modalRegistrarUsuario input[name="csrf_token"]').value;
            
            if (confirm(`¿Segura de eliminar al usuario con cédula ${cedulaUsuario}?`)) {
                
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `csrf_token=${tokenCSRF}&form=eliminar_usuario&id=${cedulaUsuario}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        event.target.closest('tr').remove();
                    } else {
                        alert("Error al eliminar: " + data.message);
                    }
                })
                .catch(error => console.error("Error en la petición:", error));
            }
        }
    });
}