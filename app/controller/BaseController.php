<?php

namespace app\controller;

use app\model\Usuario;

class BaseController {
    private ?array $requestData = null;
    
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
        if ($this->requestData !== null) {
            return $this->requestData;
        }

        $raw = file_get_contents("php://input");
        $jsonData = json_decode($raw, true);
        if (is_array($jsonData)) {
            $this->requestData = $jsonData;
        } elseif (!empty($_POST)) {
            $this->requestData = $_POST;
        } else {
            $this->requestData = [];
        }

        if (in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $token = $this->requestData['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if ($sessionToken === '' || $token === '' || !hash_equals($sessionToken, (string)$token)) {
                code(403);
                $this->jsonResponse('error', 'CSRF token inválido o ausente');
            }
        }

        return $this->requestData;
    }
}