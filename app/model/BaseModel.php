<?php
namespace app\model;

use app\config\Config;

class BaseModel {
    protected $pdo;
    public function __construct() {
        $this->pdo = Config::conexion();
    }
}