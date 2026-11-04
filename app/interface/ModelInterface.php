<?
namespace App\interface;
interface ModelInterface {
    protected function response($status, $message, $data, $redirect);
}