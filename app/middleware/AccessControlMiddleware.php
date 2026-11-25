<?php
namespace app\middleware;

use app\model\Usuario;

class AccessControlMiddleware {
    private const PERMISOS = [
        "gestionar_usuarios",
        "ver_consultas",
        "realizar_consulta",
        "modificar_consulta",
        "generar_reportes",
        "gestionar_roles_permisos",
        "gestionar_condiciones",
        "gestionar_oferta_academica"
    ];

    private const RUTAS_PROTEGIDAS = [
        "perfil",
        "usuarios",
        "consultas",
        "configuracion",
        "sesion",
        "condiciones",
        "oferta",
        "sedes",
        "sedes-carreras"
    ];

    private const BUSQUEDAS_CONSULTAS = [
        "buscar_patologia",
        "buscar_paciente",
        "buscar_consultas_paciente",
        "buscar_condicion",
        "buscar_condiciones_paciente"
    ];

    public function handle(string $paginaActual): array {
        $usuarioModel = new Usuario();
        $permisosUsuario = array_fill_keys(self::PERMISOS, false);
        $autenticado = isset($_SESSION["cedula"]);

        if ($autenticado) {
            foreach (self::PERMISOS as $permiso) {
                $permisosUsuario[$permiso] = $usuarioModel->tienePermiso($_SESSION["cedula"], $permiso);
            }
        }

        if (!$autenticado && in_array($paginaActual, self::RUTAS_PROTEGIDAS, true)) {
            header("Location: login");
            exit();
        }

        if ($autenticado) {
            $requierePermiso = [
                "usuarios" => "gestionar_usuarios",
                "consultas" => "ver_consultas",
                "oferta" => "gestionar_oferta_academica",
                "sedes" => "gestionar_oferta_academica",
                "sedes-carreras" => "gestionar_oferta_academica"
            ];

            if (isset($requierePermiso[$paginaActual]) && !$permisosUsuario[$requierePermiso[$paginaActual]]) {
                header("Location: perfil");
                exit();
            }

            if (
                $paginaActual === "configuracion"
                && !$permisosUsuario["gestionar_roles_permisos"]
                && !$permisosUsuario["gestionar_condiciones"]
            ) {
                header("Location: perfil");
                exit();
            }
        }

        if (($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST") {
            $csrfToken = $_POST["csrf_token"] ?? null;
            $sessionToken = $_SESSION["csrf_token"] ?? "";

            if (
                !is_string($csrfToken)
                || $csrfToken === ""
                || $sessionToken === ""
                || !hash_equals($sessionToken, $csrfToken)
            ) {
                http_response_code(403);
                exit("CSRF token validation failed");
            }
        }

        if (
            in_array($paginaActual, self::BUSQUEDAS_CONSULTAS, true)
            && !$permisosUsuario["ver_consultas"]
        ) {
            header("Content-Type: application/json");
            echo json_encode([]);
            exit();
        }

        return $permisosUsuario;
    }
}
