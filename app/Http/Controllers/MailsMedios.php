<?php

namespace App\Http\Controllers;

use App\helpers\GranString;
use App\Mail\MailableEstadoMedios;
use DateTime;
use Illuminate\Support\Facades\Mail;

class MailsMedios extends Controller
{
    public function principal()
    {
        
        $infoGigante = GranString::peroEstoQueEsss();
        $infoGigante = (strtolower($infoGigante));
        
        preg_match_all("/salón: ([A-Z0-9]+)/i", $infoGigante, $matches);
        $salones = implode(", ", $matches[1]);
        if (strcmp("", $salones) === 0) {
            $salones = "No hay salones pendientes";
        }
        preg_match_all("/hdmi\s([1-9]|1[0-9]|20)/i", $infoGigante, $matches);
        foreach ($matches[1] as $index => $match) {
            $matches[1][$index] = 'HDMI#'. $match;
        }
        $HDMI = implode(", ", $matches[1]);
        if (strcmp("", $HDMI) === 0) {
            $HDMI = "No hay HDMI pendientes";
        }

        preg_match_all("/\b[pP][0-9]+\b/", $infoGigante, $matches);
        $computador = implode(", ", $matches[0]);
        if (strcmp("", $computador) === 0) {
            $computador = "No hay computadores pendientes";
        }

//dd($salones,$HDMI,$computador);


        //<editor-fold desc="extraer fecha">
        // Obtener la fecha de hoy en el mismo formato
        $hoy = date("Y-m-d");
        preg_match_all("/usuario:\s*(.+?)\s*[\r\n]+.*?fecha:\s*(\w+ \d{1,2} de \w+ del \d{4} \d{2}:\d{2})/s", $infoGigante, $UserMatches, PREG_SET_ORDER);
        $demoras = "";
        foreach ($UserMatches as $userMatch) {
            $usuario = trim($userMatch[1]);
            $fecha2 = trim($userMatch[2]);

            $fechaLimpia = trim(preg_replace("/^(lunes|martes|miércoles|jueves|viernes|sábado|domingo)\s+|\s*del\s*/i", " ", $fecha2));

            $mesesEn = [
                "enero" => "January",
                "febrero" => "February",
                "marzo" => "March",
                "abril" => "April",
                "mayo" => "May",
                "junio" => "June",
                "julio" => "July",
                "agosto" => "August",
                "septiembre" => "September",
                "octubre" => "October",
                "noviembre" => "November",
                "diciembre" => "December"
            ];
            $fechaLimpia = str_replace(" de ", " ", $fechaLimpia);

            $fechaLimpia = strtr($fechaLimpia, $mesesEn);
            $fecha = DateTime::createFromFormat("j F Y H:i", $fechaLimpia);
            if (!$fecha) {
                dd(
                    "Error en la conversión de la fecha\n",
                    DateTime::getLastErrors()
                );
            }

            $StringFecha = $fecha->format("Y-m-d"); // Formato estándar de MySQL

            if ($StringFecha !== $hoy) {
                $demoras .= "La persona $usuario se ha demorado con el prestamo que genero el $StringFecha\n\n";
            }
        }
        //</editor-fold>


        preg_match_all("/fin: \d+\s+➕elementos prestados:\s+(.*?)(\n|🗑️)/s", $infoGigante, $matches);
        $HayObservaciones = !empty($matches[1]);
        $observaciones = [];
        if ($HayObservaciones) {
            foreach ($matches[1] as $match) {
                $observaciones[] = trim($match) . PHP_EOL;
            }
        }


        if (strcmp($demoras, "") !== 0) {
            $demoras = "Incumplimientos:  " . $demoras . "\n\n";
        } else {
            if (!$HayObservaciones)
                $observaciones[0] = "Sin observaciones adicionales";
        }
        $Observaciones = implode(",", $observaciones);

        if (strlen($infoGigante) > 100) {
            Mail::
            to("alejandro.madrid@colmayor.edu.co")
                ->cc(["simon.pelaez@colmayor.edu.co","tecnologia@colmayor.edu.co","viceadministrativa@colmayor.edu.co"])
                ->send(new MailableEstadoMedios([
                    "observaString" => $Observaciones,
                    "computador" => $computador,
                    "HDMI" => $HDMI,
                    "salones" => $salones,
                    "demoras" => $demoras,
                ]));
            return view('emails.estadoCopiar', [
                'mailData' => [
                    "observaString" => $Observaciones,
                    "computador" => $computador,
                    "HDMI" => $HDMI,
                    "salones" => $salones,
                    "demoras" => $demoras,
                ]
            ]);
        } else {
            echo 'Email no enviado';
            Mail::to("alejandro.madrid@colmayor.edu.co")->cc('ajelof2@gmail.com')
                ->send(new MailableEstadoMedios([
                    "observaString" => 'no se envio',
                    "computador" => 'no se envio',
                    "HDMI" => 'no se envio',
                    "salones" => 'no se envio',
                    "demoras" => 'no se envio',
                ]));
        }

    }
}
