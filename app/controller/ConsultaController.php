<?php

namespace app\controller;

use app\model\Consulta;
use app\model\Usuario;
use app\controller\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use app\face\ContFace;

class ConsultaController extends BaseController implements ContFace {

    public function consultar($id = null) {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para ver consultas");
        }

        $modeloConsulta = new Consulta();
        $idConsulta = $id ?? (isset($_GET['id']) ? (int)$_GET['id'] : null);
        if ($idConsulta !== null && $idConsulta > 0) {
            $consulta = $modeloConsulta->obtenerConsultaPorId($idConsulta);
            if ($consulta) {
                code(200);
                $this->jsonResponse("ok", "Consulta obtenida con éxito", $consulta);
            } else {
                code(404);
                $this->jsonResponse("error", "No se encontró la consulta médica");
            }
        }

        $query = isset($_GET['query']) ? trim($_GET['query']) : (isset($_GET['q']) ? trim($_GET['q']) : '');
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;

        if (empty($query)) {
            $resultados = $modeloConsulta->obtenerConsultasRecientes($limit > 0 ? $limit : 10);
        } else {
            $resultados = $modeloConsulta->buscarConsultas($query, $limit > 0 ? $limit : 20);
        }

        code(200);
        $this->jsonResponse("ok", "Consultas obtenidas con éxito", $resultados ?: []);
    }

    public function buscarConsultas($id = null) {
        $this->consultar($id);
    }

    public function obtenerConsulta($id = null) {
        $this->consultar($id);
    }

    public function registrar() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("realizar_consulta")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para registrar consultas");
        }

        $data = $this->getRequestData();

        $cedulaPaciente = cleanValue($data, 'cedula_paciente') ?? cleanValue($data, 'cedula');
        $motivo         = cleanValue($data, 'motivo_de_visita') ?? cleanValue($data, 'motivo');
        $observaciones  = cleanValue($data, 'observaciones') ?? '';
        $medicamento    = cleanValue($data, 'medicamento_suministrado') ?? cleanValue($data, 'medicamento') ?? '';

        $sintomas = $data['sintomas'] ?? [];
        if (!is_array($sintomas)) {
            $sintomas = array_filter(array_map('trim', explode(',', (string)$sintomas)));
        }

        $diagnosticos = $data['diagnosticos'] ?? [];
        if (!is_array($diagnosticos)) {
            $diagnosticos = array_filter(array_map('trim', explode(',', (string)$diagnosticos)));
        }

        $condiciones = $data['condiciones'] ?? [];
        if (!is_array($condiciones)) {
            $condiciones = array_filter(array_map('intval', explode(',', (string)$condiciones)));
        }

        $cedulaMedico = $_SESSION['cedula'] ?? '';

        if (empty($cedulaPaciente) || empty($motivo) || empty($cedulaMedico)) {
            code(400);
            $this->jsonResponse("error", "La consulta no fue registrada, revisa que todos los campos requeridos estén llenos");
        }

        $modeloConsulta = new Consulta();
        $resultado = $modeloConsulta->registrarConsulta(
            $cedulaPaciente, $cedulaMedico, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento
        );

        if ($resultado === true) {
            code(201);
            $this->jsonResponse("ok", "La consulta fue registrada exitosamente");
        } else {
            code(400);
            $this->jsonResponse("error", "La consulta no pudo ser registrada");
        }
    }

    public function actualizar() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("modificar_consulta")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para actualizar consultas");
        }

        $data = $this->getRequestData();

        $idConsulta    = isset($data['id_consulta']) ? (int)$data['id_consulta'] : (isset($data['id']) ? (int)$data['id'] : 0);
        $motivo        = cleanValue($data, 'motivo_de_visita') ?? cleanValue($data, 'motivo');
        $observaciones = cleanValue($data, 'observaciones') ?? '';
        $medicamento   = cleanValue($data, 'medicamento_suministrado') ?? cleanValue($data, 'medicamento') ?? '';

        $sintomas = $data['sintomas'] ?? [];
        if (!is_array($sintomas)) {
            $sintomas = array_filter(array_map('trim', explode(',', (string)$sintomas)));
        }

        $diagnosticos = $data['diagnosticos'] ?? [];
        if (!is_array($diagnosticos)) {
            $diagnosticos = array_filter(array_map('trim', explode(',', (string)$diagnosticos)));
        }

        $condiciones = $data['condiciones'] ?? [];
        if (!is_array($condiciones)) {
            $condiciones = array_filter(array_map('intval', explode(',', (string)$condiciones)));
        }

        if ($idConsulta <= 0 || empty($motivo)) {
            code(400);
            $this->jsonResponse("error", "ID de consulta y motivo son obligatorios");
        }

        $modeloConsulta = new Consulta();
        $resultado = $modeloConsulta->actualizarConsulta(
            $idConsulta, $motivo, $observaciones, $sintomas, $diagnosticos, $condiciones, $medicamento
        );

        if ($resultado === true) {
            code(200);
            $this->jsonResponse("ok", "La consulta fue actualizada exitosamente");
        } else {
            code(400);
            $this->jsonResponse("error", "No se pudo actualizar la consulta");
        }
    }

    public function generarReporteMorbilidad() {
        if (empty($_SESSION['cedula'])) {
            code(401);
            $this->jsonResponse("error", "Debe iniciar sesión");
        }

        if (!$this->checkPerm("generar_reportes")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos para generar reportes");
        }

        $data = $this->getRequestData();
        $fechaInicio = cleanValue($data, 'fecha_inicio') ?? ($_GET['fecha_inicio'] ?? '');
        $fechaFin    = cleanValue($data, 'fecha_fin') ?? ($_GET['fecha_fin'] ?? '');

        if (empty($fechaInicio) || empty($fechaFin)) {
            code(400);
            $this->jsonResponse("error", "Debe especificar la fecha de inicio y la fecha de fin.");
        }

        if (strtotime($fechaInicio) > strtotime($fechaFin)) {
            code(400);
            $this->jsonResponse("error", "La fecha de inicio no puede ser posterior a la fecha de fin.");
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json') || isset($_GET['json'])) {
            $modeloConsulta = new Consulta();
            $datos = $modeloConsulta->obtenerDatosReporteMorbilidad($fechaInicio, $fechaFin);
            code(200);
            $this->jsonResponse("ok", "Datos de reporte de morbilidad", $datos);
        }

        $modeloConsulta = new Consulta();
        $datos = $modeloConsulta->obtenerDatosReporteMorbilidad($fechaInicio, $fechaFin);
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('La extensión GD de PHP es necesaria para generar los gráficos del reporte.');
        }

        $usuario = (new Usuario())->login($_SESSION['cedula']);
        $usuarioData = $usuario['data'] ?? [];
        $nombreGenerador = trim(($usuarioData['nombre'] ?? '') . ' ' . ($usuarioData['apellido'] ?? ''));
        $nombreGenerador = $nombreGenerador !== '' ? $nombreGenerador : 'Médico de Guardia';
        $escape = static fn($value): string => htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
        $fechaInicioTexto = $escape(date('d/m/Y', strtotime($fechaInicio)));
        $fechaFinTexto = $escape(date('d/m/Y', strtotime($fechaFin)));
        $pacientesUnicos = count(array_unique(array_filter(array_column($datos['registros'], 'paciente_cedula'))));

        $logoPath = dirname(__DIR__, 2) . '/public/assets/media/img/uptaeb.jpg';
        $logoHtml = '<div style="width:55px;height:55px;background:#1e3a8a;color:#fff;font-weight:bold;font-size:16px;line-height:55px;text-align:center;border-radius:4px;">UPTAEB</div>';
        if (is_file($logoPath)) {
            $logoHtml = '<img src="data:image/jpeg;base64,' . base64_encode((string)file_get_contents($logoPath)) . '" style="height:55px;width:auto;max-width:80px;">';
        }

        $rgbColores = [[59,130,246], [16,185,129], [245,158,11], [239,68,68], [139,92,246], [100,116,139], [236,72,153], [20,184,166]];
        $hexColores = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#64748b', '#ec4899', '#14b8a6'];
        $prepararDatosPastel = static function (array $items, string $valueKey, string $labelKey, int $maxSlices = 5): array {
            usort($items, static fn($a, $b) => (int)$b[$valueKey] <=> (int)$a[$valueKey]);
            $total = array_sum(array_map(static fn($item) => (int)$item[$valueKey], $items));
            if ($total <= 0) return [];

            $result = [];
            $otros = 0;
            foreach ($items as $index => $item) {
                if ($index < $maxSlices) {
                    $value = (int)$item[$valueKey];
                    $result[] = ['label' => $item[$labelKey], 'value' => $value, 'pct' => round($value / $total * 100)];
                } else {
                    $otros += (int)$item[$valueKey];
                }
            }
            if ($otros > 0) {
                $result[] = ['label' => 'Otros', 'value' => $otros, 'pct' => round($otros / $total * 100)];
            }
            return $result;
        };
        $generarPastelPNG = static function (array $chartData) use ($rgbColores): string {
            $size = 400;
            $image = imagecreatetruecolor($size, $size);
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $white);

            if (empty($chartData)) {
                $gray = imagecolorallocate($image, 148, 163, 184);
                imagestring($image, 5, 170, 193, 'Sin datos', $gray);
            } else {
                $total = array_sum(array_column($chartData, 'value'));
                $startAngle = 270;
                $accumulated = 0;
                foreach ($chartData as $index => $item) {
                    $colorData = $rgbColores[$index % count($rgbColores)];
                    $color = imagecolorallocate($image, ...$colorData);
                    $accumulated += $item['value'];
                    $endAngle = $index === count($chartData) - 1
                        ? 630
                        : 270 + (int)round($accumulated / $total * 360);
                    imagefilledarc($image, 200, 200, 360, 360, $startAngle, $endAngle, $color, IMG_ARC_PIE);
                    $startAngle = $endAngle;
                }
                $border = imagecolorallocate($image, 203, 213, 225);
                imagearc($image, 200, 200, 360, 360, 0, 360, $border);
            }

            ob_start();
            imagepng($image);
            $png = base64_encode((string)ob_get_clean());
            return $png;
        };
        $htmlLeyenda = static function (array $chartData) use ($hexColores, $escape): string {
            if (empty($chartData)) return '<div style="font-size:7px;color:#94a3b8;text-align:center;padding:4px;">Sin datos</div>';
            $html = '<table style="width:100%;border-collapse:collapse;margin-top:4px;">';
            foreach ($chartData as $index => $item) {
                $label = (string)$item['label'];
                if (mb_strlen($label) > 22) $label = mb_substr($label, 0, 20) . '..';
                $html .= '<tr><td style="width:8px;padding:1px 3px 1px 0;"><div style="width:6px;height:6px;background:' . $hexColores[$index % count($hexColores)] . ';"></div></td>'
                    . '<td style="font-size:7px;color:#334155;font-weight:bold;padding:1px 2px;">' . $escape($label) . '</td>'
                    . '<td style="font-size:7px;color:#64748b;text-align:right;padding:1px 0;white-space:nowrap;">' . (int)$item['value'] . ' (' . (int)$item['pct'] . '%)</td></tr>';
            }
            return $html . '</table>';
        };

        $datosBeneficiarios = $prepararDatosPastel($datos['tipos_beneficiario'], 'total', 'nombre_tipo');
        $datosNucleos = $prepararDatosPastel($datos['nucleos'], 'total', 'nombre_nucleo');
        $datosPnfs = $prepararDatosPastel($datos['pnfs'], 'total', 'nombre_pnf');
        $imgBeneficiarios = $generarPastelPNG($datosBeneficiarios);
        $imgNucleos = $generarPastelPNG($datosNucleos);
        $imgPnfs = $generarPastelPNG($datosPnfs);
        $topPatologias = array_slice($datos['patologias'], 0, 10);
        $topCondiciones = array_slice($datos['condiciones_cronicas'], 0, 10);

        $html = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><style>
            @page { margin: 24px 28px; }
            body { font-family: Helvetica, Arial, sans-serif; color: #1e293b; font-size: 10px; line-height: 1.3; }
            .hdr { width:100%; border-bottom:2px solid #1e3a8a; padding-bottom:6px; margin-bottom:8px; }
            table { width:100%; border-collapse:collapse; } .hdr td { vertical-align:middle; }
            .hdr-info { padding-left:10px; } .hdr-info h1 { font-size:12px; color:#1e3a8a; margin:0 0 2px; text-transform:uppercase; }
            .hdr-info p { margin:0; color:#64748b; font-size:9.5px; font-weight:bold; } .hdr-meta { text-align:right; font-size:8.5px; color:#475569; line-height:1.4; }
            .kpi-card { background:#f8fafc; border:1px solid #e2e8f0; padding:5px; text-align:center; }
            .kpi-num { font-size:17px; font-weight:bold; color:#1e3a8a; } .kpi-lbl { font-size:7px; color:#64748b; text-transform:uppercase; font-weight:bold; }
            .chart-cell { width:33.3%; text-align:center; border:1px solid #e2e8f0; padding:4px; vertical-align:top; }
            .chart-title,.sec-title { font-weight:bold; font-size:8px; color:#1e3a8a; text-transform:uppercase; }
            .cols-t td { width:50%; vertical-align:top; } .col-l { padding-right:10px; } .col-r { padding-left:10px; }
            .sec-title { font-size:9px; color:#0f172a; border-bottom:1px solid #cbd5e1; padding-bottom:2px; margin:6px 0 4px; }
            .dt th { background:#1e3a8a; color:#fff; text-align:left; padding:2px 4px; font-size:7px; text-transform:uppercase; }
            .dt td { padding:2px 4px; border-bottom:1px solid #f1f5f9; font-size:7.5px; } .dt tr:nth-child(even) td { background:#f8fafc; }
        </style></head><body>
        <div class="hdr"><table><tr><td style="width:80px;">' . $logoHtml . '</td>
        <td class="hdr-info"><h1>Universidad Polit&eacute;cnica Territorial Andr&eacute;s Eloy Blanco</h1><p>Servicio M&eacute;dico &mdash; Reporte de Morbilidad</p></td>
        <td class="hdr-meta"><strong>Periodo:</strong> ' . $fechaInicioTexto . ' al ' . $fechaFinTexto . '<br><strong>Emisi&oacute;n:</strong> ' . date('d/m/Y H:i:s') . '<br><strong>Generado por:</strong> ' . $escape($nombreGenerador) . '</td></tr></table></div>
        <table style="margin-bottom:8px;"><tr><td style="width:33%;padding-right:5px;"><div class="kpi-card"><div class="kpi-num">' . (int)$datos['total_consultas'] . '</div><div class="kpi-lbl">Consultas M&eacute;dicas</div></div></td>
        <td style="width:34%;padding:0 3px;"><div class="kpi-card"><div class="kpi-num">' . count($datos['patologias']) . '</div><div class="kpi-lbl">Patolog&iacute;as Identificadas</div></div></td>
        <td style="width:33%;padding-left:5px;"><div class="kpi-card"><div class="kpi-num">' . $pacientesUnicos . '</div><div class="kpi-lbl">Pacientes Atendidos</div></div></td></tr></table>
        <table style="margin-bottom:8px;"><tr>
        <td class="chart-cell"><div class="chart-title">Beneficiarios</div><img src="data:image/png;base64,' . $imgBeneficiarios . '" style="width:90px;height:90px;">' . $htmlLeyenda($datosBeneficiarios) . '</td>
        <td class="chart-cell"><div class="chart-title">N&uacute;cleos / Sedes</div><img src="data:image/png;base64,' . $imgNucleos . '" style="width:90px;height:90px;">' . $htmlLeyenda($datosNucleos) . '</td>
        <td class="chart-cell"><div class="chart-title">Programas (PNF)</div><img src="data:image/png;base64,' . $imgPnfs . '" style="width:90px;height:90px;">' . $htmlLeyenda($datosPnfs) . '</td></tr></table>
        <table class="cols-t"><tr><td class="col-l"><div class="sec-title">Diagn&oacute;sticos CIE-10 m&aacute;s Comunes</div><table class="dt"><tr><th>C&oacute;digo</th><th>Patolog&iacute;a</th><th>Casos</th></tr>';
        if (empty($topPatologias)) {
            $html .= '<tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:6px;">No hay diagn&oacute;sticos</td></tr>';
        } else {
            foreach ($topPatologias as $patologia) {
                $html .= '<tr><td style="font-weight:bold;color:#1e3a8a;">' . $escape($patologia['codigo_icd']) . '</td><td>' . $escape($patologia['patologia']) . '</td><td style="text-align:right;font-weight:bold;">' . (int)$patologia['total'] . '</td></tr>';
            }
        }
        $html .= '</table></td><td class="col-r"><div class="sec-title">Condiciones M&eacute;dicas m&aacute;s Comunes</div><table class="dt"><tr><th>Condici&oacute;n</th><th>Pacientes</th></tr>';
        if (empty($topCondiciones)) {
            $html .= '<tr><td colspan="2" style="text-align:center;color:#94a3b8;padding:6px;">No hay condiciones</td></tr>';
        } else {
            foreach ($topCondiciones as $condicion) {
                $html .= '<tr><td style="font-weight:bold;">' . $escape($condicion['nombre_condicion']) . '</td><td style="text-align:right;font-weight:bold;">' . (int)$condicion['total'] . '</td></tr>';
            }
        }
        $html .= '</table></td></tr></table></body></html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $nombreArchivo = 'Reporte_Morbilidad_' . $fechaInicio . '_a_' . $fechaFin . '.pdf';
        $contenidoPdf = $dompdf->output();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . strlen($contenidoPdf));
        echo $contenidoPdf;
        exit();
    }

    public function buscarPatologia() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarPatologia($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function buscarPaciente() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarUsuario($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function obtenerConsultasPaciente($cedula = null) {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $ced = $cedula ?? (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');
        if (empty($ced)) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->obtenerConsultasPorPaciente($ced);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function buscarCondicion() {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->buscarCondicion($q);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

    public function obtenerCondicionesPaciente($cedula = null) {
        if (!$this->checkPerm("ver_consultas")) {
            code(403);
            $this->jsonResponse("error", "No tiene permisos");
        }

        $ced = $cedula ?? (isset($_GET['cedula']) ? trim($_GET['cedula']) : '');
        if (empty($ced)) {
            $this->jsonResponse("ok", "Resultados", []);
        }

        $modeloConsulta = new Consulta();
        $resultados = $modeloConsulta->obtenerCondicionesPaciente($ced);
        $this->jsonResponse("ok", "Resultados", $resultados ?: []);
    }

}