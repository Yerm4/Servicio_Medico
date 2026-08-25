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

function reload() {
    $url = $_SERVER["REQUEST_URI"];
    header("Location: ".$url);
    die();
}

function code($num) {
    if ($num === null) {
        return "";
    }
    return http_response_code($num);
}

function cleanString($value): ?string {
    if (!is_string($value) && !is_int($value) && !is_float($value)) {
        return null; 
    }

    $trimmed = trim((string)$value);
    return $trimmed !== '' ? $trimmed : null;
}


function cleanValue(?array $data, string $key): ?string {
    return cleanString($data[$key] ?? null);
}