document.addEventListener('input', (event) => {
    const target = event.target;
    if (!target || !target.name) return;

    const campo = target.name;

    // 1. Validaciones para Cédulas (Solo números, máx. 8 dígitos + feedback visual)
    if (campo === 'cedula') {
        target.value = target.value.replace(/\D/g, '');
        
        if (target.value.length > 8) {
            target.value = target.value.slice(0, 8);
        }

        // Indicador visual de longitud (entre 7 y 8 dígitos)
        if (target.value.length < 7 || target.value.length > 8) {
            target.style.border = '2px red solid';
        } else {
            target.style.border = '2px green solid';
        }
    }

    // 2. Validaciones para Teléfonos (Solo números, máx. 20 dígitos)
    else if (campo === 'tlfprincipal' || campo === 'tlfemergencia') {
        target.value = target.value.replace(/\D/g, '');
        
        if (target.value.length > 20) {
            target.value = target.value.slice(0, 20);
        }
    }

    // 3. Validaciones para Nombres y Apellidos (Solo letras, acentos y espacios, máx. 30 caracteres)
    else if (campo === 'nombre' || campo === 'apellido' || campo === 'nombre_contacto_emergencia') {
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
        
        if (target.value.length > 30) {
            target.value = target.value.slice(0, 30);
        }
    }

    // 4. Validaciones para Dirección (Solo letras, acentos y espacios, máx. 40 caracteres)
    else if (campo === 'direccion') {
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '');
        
        if (target.value.length > 40) {
            target.value = target.value.slice(0, 40);
        }
    }
});