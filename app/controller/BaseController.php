namespace app\controller;

use app\model\Usuario;

abstract class BaseController {
    
    protected function jsonResponse($status, $message = "", $data = null, $redirect = null) {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data,
            "redirect" => $redirect
        ]); 
        exit();
    }

    protected function checkPerm(string $permiso): bool {
        if (empty($_SESSION['cedula'])) {
            return false;
        }
        $userModel = new Usuario();
        return $userModel->tienePermiso($_SESSION['cedula'], $permiso);
    }

    protected function getRequestData(): array {
        $raw = file_get_contents("php://input");
        $jsonData = json_decode($raw, true);
        if (is_array($jsonData)) {
            return $jsonData;
        }
        if (!empty($_POST)) {
            return $_POST;
        }
        return [];
    }
}