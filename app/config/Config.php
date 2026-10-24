<?php
namespace app\config;

use PDO;
use PDOException;

class Config {

    private static $pdo = null;

    public static function conexion() {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = $_ENV["DB_HOST"] ?? "localhost";
        $db = $_ENV["DB_NAME"] ?? "servicio_medico";
        $user = $_ENV["DB_USER"] ?? "root";
        $password = $_ENV["DB_PASS"] ?? "";

        try {
            self::$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password); 
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return self::$pdo;
        }

        catch(PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

    }
}