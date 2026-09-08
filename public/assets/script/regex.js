document.addEventListener("DOMContentLoaded", () => {
    // -------------------------------------------------------------------------
    // 1. CONFIGURACIÓN DE LÍMITES EN FECHA DE NACIMIENTO
    // -------------------------------------------------------------------------
    const inputsFecha = document.querySelectorAll("input[name=fecha_nacimiento]");
    
    if (inputsFecha.length > 0) {
        const hoy = new Date();
        const hoyFormateada = hoy.toLocaleDateString("sv-SE");

        const añoMinimo = hoy.getFullYear() - 110;
        const fechaMinFormateada = `${añoMinimo}-${String(hoy.getMonth() + 1).padStart(2, "0")}-${String(hoy.getDate()).padStart(2, "0")}`;

        inputsFecha.forEach(input => {
            input.max = hoyFormateada;
            input.min = fechaMinFormateada;
        });
    }
});

// -------------------------------------------------------------------------
// 2. MASCARADO Y VALIDACIÓN EN TIEMPO REAL (DELEGACIÓN DE EVENTOS)
// -------------------------------------------------------------------------
document.addEventListener("input", (event) => {
    const target = event.target;
    if (!target || !target.name) return;

    const campo = target.name;

    // Cédulas: Solo números, máx. 8 dígitos y feedback visual
    if (campo === "cedula") {
        target.value = target.value.replace(/\D/g, "");

        if (target.value.length > 8) {
            target.value = target.value.slice(0, 8);
        }

        target.style.border = (target.value.length >= 7 && target.value.length <= 8) 
            ? "2px solid green" 
            : "2px solid red";
    }

    // Teléfonos: Solo números, máx. 20 dígitos
    else if (campo === "tlfprincipal" || campo === "tlfemergencia") {
        target.value = target.value.replace(/\D/g, "");

        if (target.value.length > 20) {
            target.value = target.value.slice(0, 20);
        }
    }

    // Nombres y Apellidos: Solo letras, acentos y espacios, máx. 30 caracteres
    else if (["nombre", "apellido", "nombre_contacto_emergencia"].includes(campo)) {
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, "");

        if (target.value.length > 30) {
            target.value = target.value.slice(0, 30);
        }
    }

    // Dirección: Solo letras, acentos y espacios, máx. 40 caracteres
    else if (campo === "direccion") {
        target.value = target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, "");

        if (target.value.length > 40) {
            target.value = target.value.slice(0, 40);
        }
    }
});