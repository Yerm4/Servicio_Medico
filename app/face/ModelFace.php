<?
namespace App\face;

interface ModelFace {
    public function response($status, $message, $data, $redirect);
}