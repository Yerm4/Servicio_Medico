<?php 
function e(?string $value, bool $doubleEncode = true): string {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
}

function calcularEdad(?string $fechaNacimiento): string {
    if (empty($fechaNacimiento)) {
        return "No registrado";
    }

    try {
        $fechaNacimiento = new DateTime($fechaNacimiento);
        $fechaActual = new DateTime();
        if ($fechaNacimiento > $fechaActual) {
            return "Fecha invalida";
        }

        if ((int)$fechaNacimiento->format('Y') < 1900) {
            return 'Fecha inválida';
        }

        return (string)$fechaActual->diff($fechaNacimiento)->y;
    } catch (Exception $e) {
        return 'Error de formato';
    }
}