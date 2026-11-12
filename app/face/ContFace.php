<?php

namespace app\face;

interface Contface {
    public function registrar();
    public function actualizar();
    public function consultar($id = null);
}