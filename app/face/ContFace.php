<?php

namespace app\face;

interface ContFace {
    public function jsonResponse($status, $message = "", $data = null, $redirect = null);

    public function checkPerm(string $permiso);

    public function getRequestData();
}