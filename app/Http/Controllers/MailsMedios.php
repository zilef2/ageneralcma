<?php

namespace App\Http\Controllers;

use App\Mail\EstadoMedios;
use DateTime;
use Illuminate\Support\Facades\Mail;

class MailsMedios extends Controller
{
    public function principal(): void
    {

        $infoGigante = (strtolower($this->infoGigante));
        preg_match_all("/salón: ([A-Z0-9]+)/i", $infoGigante, $matches);
        $salones = implode(", ", $matches[1]);
        if (strcmp("", $salones) === 0) {
            $salones = "No hay salones pendientes";
        }
        preg_match_all("/hdmi([0-9]+)/", $infoGigante, $matches);
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


        echo "<br>Llaves pendientes: " . $salones . "\n";
        echo "<br>Computadores: " . $computador . "\n\n";
        echo "<br>HDMI: " . $HDMI . "\n\n";

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
            echo "<br> $demoras";
        } else {
            if (!$HayObservaciones)
                $observaciones[0] = "Sin observaciones adicionales";
        }
        $Observaciones = implode(",", $observaciones);
        echo "<br>Observaciones: " . $Observaciones . "\n\n";

        if (strlen($this->infoGigante) > 100) {
            Mail::
//            to("alejandro.madrid@colmayor.edu.co")
            to("tecnologia@colmayor.edu.co")
                ->cc("viceadministrativa@colmayor.edu.co")
                ->cc("simon.pelaez@colmayor.edu.co")
                ->cc("alejandro.madrid@colmayor.edu.co")
                ->send(new EstadoMedios([
                    "observaString" => $Observaciones,
                    "computador" => $computador,
                    "HDMI" => $HDMI,
                    "salones" => $salones,
                    "demoras" => $demoras,
                ]));

            echo "<br> <br>Email enviado";
        } else {
            echo "<br> <br>Email NO enviado";
        }
    }

    public string $infoGigante = "
   Préstamo generado:
Salón: C105

Usuario: Edwin Jader Suaza Estrada

Cédula: 8432186

Dependencia: Facultad De Ciencias Sociales Y Educacion

Prestó: Practicante

Fecha:lunes 7 de octubre del 2024 18:04

Inicio: 18

Fin: 22

Elementos prestados:
➕
Llave 1

🗑️
Devolver todo
Préstamo generado:
Usuario: Catalina Escobar Tovar

Cédula: 1015393469

Dependencia: Facultad De Arquitectura E Ingenieria

Prestó: Practicante

Fecha:lunes 7 de octubre del 2024 09:17

Inicio: 9

Fin: 17

devuelve mañana a las 6 a.m

🗑️
Elementos prestados:
➕
P39628
🗑️
Devolver todo
Préstamo generado:
Usuario: Maria Camila Gonzalez Alvarez

Cédula: 1193131567

Dependencia: Vicerrectoría Administrativa y Financiera

Prestó: Practicante

Fecha:jueves 3 de octubre del 2024 15:33

Inicio: 15

Fin: 15

se presta hasta el 4 de octubre

🗑️
Elementos prestados:
➕
P39641

";
}
