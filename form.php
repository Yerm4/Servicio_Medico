//$form = isset($_POST["form"]) ? $_POST["form"] : '';

/*switch($form) {
    case "registrar_usuario":
        if (isset($_SESSION['cedula'])) {
            if (!checkPerm("gestionar_usuarios", $userModel)) {
                $_SESSION["registro_status"] = "error";
                $_SESSION["registro_msg"] = "No tiene permisos para registrar pacientes.";
                header("Location: perfil");
                exit();
            }
        }
        $controller->Registrar();    
        break;
    case "registro_consulta":
        if (!$tieneRealizarConsulta) {
            http_response_code(403);
            exit("No tiene permisos para registrar consultas.");
        }
        $controllerConsulta->registrar();
        break;
    case "actualizar_consulta":
        if (!$tieneModificarConsulta) {
            http_response_code(403);
            exit("No tiene permisos para actualizar consultas.");
        }
        $controllerConsulta->actualizar();
        break;
    case "generar_reporte_morbilidad":
        if (!$tieneGenerarReportes) {
            http_response_code(403);
            exit("No tiene permisos para generar reportes.");
        }
        $controllerConsulta->generarReporteMorbilidad();
        break;
    case "eliminar_usuario":
        if (!checkPerm("gestionar_usuarios", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'No tiene permisos para eliminar usuarios.']);
            exit();
        }
        $controller->eliminar(); 
        break;
    case "login":
        $controller->login();
        break;
    case "buscar_usuario":
        if (!checkPerm("gestionar_usuarios", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }
        $controller->buscar();
        break;
    case "obtener_usuario":
        if (!checkPerm("gestionar_usuarios", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No tiene permisos.']);
            exit();
        }
        $controller->obtenerUsuarioPorCedula();
        break;
    case "actualizar_usuario":
        if (!checkPerm("gestionar_usuarios", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'No tiene permisos para actualizar usuarios.']);
            exit();
        }
        $controller->actualizar();
        break;
    case "buscar_consultas":
        if (!checkPerm("ver_consultas", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit();
        }
        $controllerConsulta->buscarConsultasAjax();
        break;
    case "obtener_consulta":
        if (!checkPerm("ver_consultas", $userModel)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No tiene permisos.']);
            exit();
        }
        $controllerConsulta->obtenerConsultaPorIdAjax();
        break;
    case "guardar_roles_permisos":
        if (!checkPerm("gestionar_roles_permisos", $userModel)) {
            http_response_code(403);
            exit("No tiene permisos para administrar roles y permisos.");
        }
        $roles = $userModel->obtenerRoles();
        $postedPermisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];
        foreach ($roles as $role) {
            $idRol = $role['id_rol'];
            $permisosIds = isset($postedPermisos[$idRol]) ? $postedPermisos[$idRol] : [];
            $userModel->actualizarPermisosRol($idRol, $permisosIds);
        }
        $_SESSION["registro_status"] = "success";
        $_SESSION["registro_msg"] = "¡Roles y permisos actualizados con éxito!";
        header("Location: configuracion");
        exit();
        break;
    case "registrar_rol":
        if (!checkPerm("gestionar_roles_permisos", $userModel)) {
            http_response_code(403);
            exit("No tiene permisos para administrar roles y permisos.");
        }
        $nombreRol = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';
        $descripcionRol = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
        if (!empty($nombreRol)) {
            $userModel->crearRol($nombreRol, $descripcionRol);
            $_SESSION["registro_status"] = "success";
            $_SESSION["registro_msg"] = "¡Rol creado exitosamente!";
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "El nombre del rol es obligatorio.";
        }
        header("Location: configuracion");
        exit();
        break;
    case "eliminar_rol":
        if (!checkPerm("gestionar_roles_permisos", $userModel)) {
            http_response_code(403);
            exit("No tiene permisos para administrar roles y permisos.");
        }
        $idRol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
        if ($idRol > 0) {
            $userModel->eliminarRol($idRol);
            $_SESSION["registro_status"] = "success";
            $_SESSION["registro_msg"] = "¡Rol eliminado con éxito!";
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "No se puede eliminar este rol.";
        }
        header("Location: configuracion");
        exit();
        break;
    case "actualizar_rol":
        if (!checkPerm("gestionar_roles_permisos", $userModel)) {
            http_response_code(403);
            exit("No tiene permisos para administrar roles y permisos.");
        }
        $idRol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
        $nombreRol = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';
        $descripcionRol = isset($_POST['descripcion_rol']) ? trim($_POST['descripcion_rol']) : '';
        if ($idRol > 0 && !empty($nombreRol)) {
            $userModel->actualizarRol($idRol, $nombreRol, $descripcionRol);
            $_SESSION["registro_status"] = "success";
            $_SESSION["registro_msg"] = "¡Rol actualizado con éxito!";
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "Datos del rol inválidos.";
        }
        header("Location: configuracion");
        exit();
        break;
    case "guardar_configuracion":
        if (!checkPerm("gestionar_roles_permisos", $userModel)) {
            http_response_code(403);
            exit("No tiene permisos para administrar roles y permisos.");
        }
        $rolDefecto = isset($_POST['rol_defecto']) ? (int)$_POST['rol_defecto'] : 0;
        if ($rolDefecto > 0) {
            $userModel->actualizarRolDefecto($rolDefecto);
            $_SESSION["registro_status"] = "success";
            $_SESSION["registro_msg"] = "¡Configuración general guardada con éxito!";
        } else {
            $_SESSION["registro_status"] = "error";
            $_SESSION["registro_msg"] = "Seleccione un rol válido.";
        }
        header("Location: configuracion");
        exit();
        break;
    case "registrar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->registrar();
        break;
    case "actualizar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->actualizar();
        break;
    case "eliminar_condicion":
        if (!$tieneGestionarCondiciones) {
            http_response_code(403);
            exit("No tiene permisos.");
        }
        $controllerCondicion = new CondicionController($pdo);
        $controllerCondicion->eliminar();
        break;
    case "buscar_pnfs":
        $controller = new Controller($pdo);
        $controller->buscarPnfs();
        break;
    
    // ─── TUS CASOS DE NÚCLEOS Y PNFS BIEN UBICADOS CON SEGURIDAD ───
    case "registrar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarNucleo();
        break;
    case "actualizar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->actualizarNucleo();
        break;
    case "eliminar_nucleo":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarNucleo();
        break;
    case "registrar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarPnf();
        break;
    case "actualizar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->actualizarPnf();
        break;
    case "eliminar_pnf":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarPnf();
        break;
    case "registrar_oferta":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->registrarOferta();
        break;
    case "eliminar_oferta":
        if (!$tieneGestionarOferta) { http_response_code(403); exit("No tiene permisos."); }
        $controllerOferta->eliminarOferta();
        break;
    case "obtener_pnfs_por_nucleo":
        $controllerOferta->obtenerPnfsPorNucleo();
        break;
}*/