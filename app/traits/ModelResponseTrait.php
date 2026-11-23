<?php

namespace app\traits;

trait ModelResponseTrait {
    protected function response($status, $message = "", $data = null, $redirect = null): array {
        return [
            "status"   => $status,
            "message"  => $message,
            "data"     => $data,
            "redirect" => $redirect
        ];
    }
}

?>

