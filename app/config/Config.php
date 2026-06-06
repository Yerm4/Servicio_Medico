<?php
namespace app\config;

use PDO;
use PDOException;

class Config {
    public static function conexion() {
        $host = $_ENV["DB_HOST"] ?? "localhost";
        $db = $_ENV["DB_NAME"] ?? "servicio_medico";
        $user = $_ENV["DB_USER"] ?? "root";
        $password = isset($_ENV["DB_PASS"]) ? $_ENV["DB_PASS"] : "";

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }

        catch(PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

    }
}
?>
