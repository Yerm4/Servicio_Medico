<?php
namespace app\config;

use PDO;
use PDOException;

class Config {
    public static function conexion() {
        $host = "localhost";
        $db = "servicio_medico";
        $user = "root";
        $password = "";

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }

        catch(PDOException $e) {
            echo "error";
        }

    }
}
