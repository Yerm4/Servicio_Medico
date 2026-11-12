<?php

namespace app\face;

interface Contface {
    public function registrar($id = null);
    public function actualizar($id = null);
    public function consultar($id = null);
}