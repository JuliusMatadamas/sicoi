<?php

namespace app\models;

use app\core\DbModel;
use DateTime;

class Incapacidad extends DbModel
{
    // PROPIEDADES
    public $id = NULL;
    public int $id_usuario;
    public string $fecha_inicio;
    public string $fecha_termino;
    public string $comprobante;
    public int $validacion = 0;
    public string $observaciones = '';
    public $created_at = NULL;

    public function validate()
    {
        $fI = explode('-', $this->fecha_inicio);
        if (!checkdate(intval($fI[1]), intval($fI[2]), intval($fI[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de inicio de la incapacidad no es válida.'];
        }

        $fT = explode('-', $this->fecha_termino);
        if (!checkdate(intval($fT[1]), intval($fT[2]), intval($fT[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de término de la incapacidad no es válida.'];
        }

        $date1 = new DateTime($this->fecha_inicio);
        $date2 = new DateTime($this->fecha_termino);

        if ($date1 > $date2) {
            return $res = ['eval' => false, 'message' => 'La fecha de término no puede ser anterior a la fecha de inicio.'];
        }

        $reg = $this->customQuery("SELECT incapacidades.id FROM incapacidades WHERE incapacidades.id_usuario = " . $this->id_usuario . " AND incapacidades.fecha_inicio = '" . $this->fecha_inicio . "' AND incapacidades.fecha_termino = '" . $this->fecha_termino . "' ");
        if (count($reg) > 0) {
            return $res = ['eval' => false, 'message' => "La incapacidad ya ha sido registrada."];
        }

        if ($this->comprobante == "") {
            return $res = ['eval' => false, 'message' => 'Se debe cargar el comprobante en alguno de los siguientes formatos: pdf, jpeg, jpg, png.'];
        }

        $c = explode(";base64,", $this->comprobante);
        $b64 = $c[1];
        $bin = base64_decode($b64, true);
        if (strpos($bin, '%PDF') === 0) {
            try {
                $file = RUTA_INCAPACIDADES . uniqid() . '.pdf';
                file_put_contents($file, $bin);
                $pos = strpos($file, '/public/');
                $ruta = substr($file, $pos);
                $this->comprobante = $ruta;
            } catch (\Exception $e) {
                return $res = ['eval' => false, 'message' => $e->getMessage()];
            }
        } else {
            try {
                $image_base64 = base64_decode($c[1]);
                $file = RUTA_INCAPACIDADES . uniqid() . '.png';
                file_put_contents($file, $image_base64);
                $pos = strpos($file, '/public/');
                $ruta = substr($file, $pos);
                $this->comprobante = $ruta;
            } catch (\Exception $e) {
                return $res = ['eval' => false, 'message' => $e->getMessage()];
            }
        }

        return $res = ['eval' => true];
    }

    public function validateUpdate()
    {
        $fI = explode('-', $this->fecha_inicio);
        if (!checkdate(intval($fI[1]), intval($fI[2]), intval($fI[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de inicio de la incapacidad no es válida.'];
        }

        $fT = explode('-', $this->fecha_termino);
        if (!checkdate(intval($fT[1]), intval($fT[2]), intval($fT[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de término de la incapacidad no es válida.'];
        }

        $date1 = new DateTime($this->fecha_inicio);
        $date2 = new DateTime($this->fecha_termino);

        if ($date1 > $date2) {
            return $res = ['eval' => false, 'message' => 'La fecha de término no puede ser anterior a la fecha de inicio.'];
        }

        if ($this->comprobante == "") {
            return $res = ['eval' => false, 'message' => 'Se debe cargar el comprobante en alguno de los siguientes formatos: pdf, jpeg, jpg, png.'];
        }

        $reg = $this->customQuery("SELECT incapacidades.comprobante FROM incapacidades WHERE incapacidades.id = " . $this->id);
        if (count($reg) > 0) {
            $oldComprobante = $reg[0]["comprobante"];
            $parts = explode("/", $oldComprobante);
            $last = count($parts) - 1;
            $fileOnDisk = RUTA_INCAPACIDADES . $parts[$last];

            if (file_exists($fileOnDisk)) {
                $nvoComprobante = str_replace(ENTORNO, "", $this->comprobante);
                if (strcmp($oldComprobante, $nvoComprobante) == 0) {
                    return $res = ['eval' => true];
                } else {
                    $c = explode(";base64,", $this->comprobante);
                    $b64 = $c[1];
                    $bin = base64_decode($b64, true);
                    if (strpos($bin, '%PDF') === 0) {
                        try {
                            $file = RUTA_INCAPACIDADES . uniqid() . '.pdf';
                            file_put_contents($file, $bin);
                            $pos = strpos($file, '/public/');
                            $ruta = substr($file, $pos);
                            $this->comprobante = $ruta;
                        } catch (\Exception $e) {
                            return $res = ['eval' => false, 'message' => $e->getMessage()];
                        }
                    } else {
                        try {
                            $image_base64 = base64_decode($c[1]);
                            $file = RUTA_INCAPACIDADES . uniqid() . '.png';
                            file_put_contents($file, $image_base64);
                            $pos = strpos($file, '/public/');
                            $ruta = substr($file, $pos);
                            $this->comprobante = $ruta;
                        } catch (\Exception $e) {
                            return $res = ['eval' => false, 'message' => $e->getMessage()];
                        }
                    }
                }
            } else {
                $c = explode(";base64,", $this->comprobante);
                $b64 = $c[1];
                $bin = base64_decode($b64, true);
                if (strpos($bin, '%PDF') === 0) {
                    try {
                        $file = RUTA_INCAPACIDADES . uniqid() . '.pdf';
                        file_put_contents($file, $bin);
                        $pos = strpos($file, '/public/');
                        $ruta = substr($file, $pos);
                        $this->comprobante = $ruta;
                    } catch (\Exception $e) {
                        return $res = ['eval' => false, 'message' => $e->getMessage()];
                    }
                } else {
                    try {
                        $image_base64 = base64_decode($c[1]);
                        $file = RUTA_INCAPACIDADES . uniqid() . '.png';
                        file_put_contents($file, $image_base64);
                        $pos = strpos($file, '/public/');
                        $ruta = substr($file, $pos);
                        $this->comprobante = $ruta;
                    } catch (\Exception $e) {
                        return $res = ['eval' => false, 'message' => $e->getMessage()];
                    }
                }
            }
        } else {
            $c = explode(";base64,", $this->comprobante);
            $b64 = $c[1];
            $bin = base64_decode($b64, true);
            if (strpos($bin, '%PDF') === 0) {
                try {
                    $file = RUTA_INCAPACIDADES . uniqid() . '.pdf';
                    file_put_contents($file, $bin);
                    $pos = strpos($file, '/public/');
                    $ruta = substr($file, $pos);
                    $this->comprobante = $ruta;
                } catch (\Exception $e) {
                    return $res = ['eval' => false, 'message' => $e->getMessage()];
                }
            } else {
                try {
                    $image_base64 = base64_decode($c[1]);
                    $file = RUTA_INCAPACIDADES . uniqid() . '.png';
                    file_put_contents($file, $image_base64);
                    $pos = strpos($file, '/public/');
                    $ruta = substr($file, $pos);
                    $this->comprobante = $ruta;
                } catch (\Exception $e) {
                    return $res = ['eval' => false, 'message' => $e->getMessage()];
                }
            }
        }

        return $res = ['eval' => true];
    }

    public function create()
    {
        $this->id = NULL;
        $date = date('Y-m-d');
        $this->created_at = $date;
        return $this->save();
    }

    public function delete()
    {
        // OBTENER EL COMPROBANTE
        $reg = $this->customQuery("SELECT incapacidades.comprobante FROM incapacidades WHERE incapacidades.id = " . $this->id);

        if (gettype($reg) == "string") {
            return $reg;
        }

        if (gettype($reg) == "boolean") {
            if (!$reg) {
                return $reg;
            }
        }

        if (count($reg) > 0) {
            $parts = explode("/", $reg[0]["comprobante"]);
            $last = count($parts) - 1;
            $routeFileToDelete = RUTA_INCAPACIDADES . $parts[$last];
            if (file_exists($routeFileToDelete)) {
                unlink($routeFileToDelete);
            }

            return $this->customUpdate("DELETE FROM incapacidades WHERE incapacidades.id = " . $this->id);
        } else {
            return false;
        }
    }

    public function toValidate()
    {
        $t = $this->tableName();
        $e = "empleados";
        return parent::customQuery("SELECT $t.id, CONCAT($e.nombre,' ',$e.apellido_paterno) AS 'empleado', $t.fecha_inicio, $t.fecha_termino, $t.comprobante FROM $t INNER JOIN $e ON $t.id_usuario = $e.id WHERE $t.validacion = 0 AND $t.deleted_at IS NULL");
    }

    public function tableName(): string
    {
        return 'incapacidades';
    }

    public function attributes(): array
    {
        return ['id_usuario', 'fecha_inicio', 'fecha_termino', 'comprobante', 'validacion', 'created_at', 'observaciones'];
    }

    public function attributesToUpdate(): array
    {
        return ['fecha_inicio', 'fecha_termino', 'comprobante'];
    }
}