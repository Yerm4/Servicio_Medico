<?php

namespace app\interface;

interface ContInterface {
    protected function jsonResponse($status, $message, $data, $redirect);

    protected function checkPerm(string $permiso);

    protected function getRequestData();
}