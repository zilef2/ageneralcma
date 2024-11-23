<?php

namespace App\Http\Controllers;

use App\helpers\GranString;
use App\Mail\MailableEstadoMedios;
use App\Mail\NewEnviarMail;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Mail;

class MailsMedios extends Controller
{
    public function principal()
    {

        $infoGigante = GranString::peroEstoQueEsss();

        if (strlen($infoGigante) < 20) {
            Mail::to("alejandro.madrid@colmayor.edu.co")->send(new MailableEstadoMedios([
                "observaString" => $infoGigante,
                "computador" => 'String muy corto',
                "HDMI" => 'String muy corto',
                "salones" => 'String muy corto',
                "demoras" => 'String muy corto',
            ]));
            return view('emails.estadoCopiar', [
                'mailData' => [
                    "observaString" => $infoGigante,
                    "computador" => 'String muy corto',
                    "HDMI" => 'String muy corto',
                    "salones" => 'String muy corto',
                    "demoras" => 'String muy corto',
                ]
            ]);
        }
        $infoGigante = (strtolower($infoGigante));

        preg_match_all("/salón: ([A-Z0-9]+)/i", $infoGigante, $matches);
        $salones = implode(", ", $matches[1]);
        if (strcmp("", $salones) === 0) {
            $salones = "No hay salones pendientes";
        }
        preg_match_all("/hdmi\s([1-9]|1[0-9]|20)/i", $infoGigante, $matches);
        foreach ($matches[1] as $index => $match) {
            $matches[1][$index] = 'HDMI#' . $match;
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

        $hora = intval(date('H'));
        $mins = intval(date('i'));
        $conCopia = [];
        if ($hora > 21 && $mins > 40) {
            $conCopia = ["simon.pelaez@colmayor.edu.co", "tecnologia@colmayor.edu.co", "viceadministrativa@colmayor.edu.co"];
        }

        if (strlen($infoGigante) > 3) {
//            Mail::to("alejandro.madrid@colmayor.edu.co")
//                ->cc($conCopia)
//                ->send(new MailableEstadoMedios([
//                    "observaString" => $Observaciones,
//                    "computador" => $computador,
//                    "HDMI" => $HDMI,
//                    "salones" => $salones,
//                    "demoras" => $demoras,
//                ]));
            return view('emails.estadoCopiar', [
                'mailData' => [
                    "observaString" => $Observaciones,
                    "computador" => $computador,
                    "HDMI" => $HDMI,
                    "salones" => $salones,
                    "demoras" => $demoras,
                    "conCopia" => $conCopia,
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
            return view('emails.estadoCopiar', [
                'mailData' => [
                    "observaString" => 'no se envio',
                    "computador" => 'no se envio',
                    "HDMI" => 'no se envio',
                    "salones" => 'no se envio',
                    "demoras" => 'no se envio',
                ]
            ]);
        }
    }//se dejara de usar

    public function enviar()
    {

        $conCopia = ["simon.pelaez@colmayor.edu.co", "tecnologia@colmayor.edu.co", "viceadministrativa@colmayor.edu.co"];
//        $conCopia = ["tecnologia@colmayor.edu.co"];
        $dash = new dashboardController();

        $pendientes = $dash->GetPrestamosHoy();

        $dash->WriteOnDataBaseBitacora($pendientes);

        Mail::to("alejandro.madrid@colmayor.edu.co")
            ->cc($conCopia)
            ->send(new NewEnviarMail($pendientes));

        $rando = Carbon::now()
            ->format('d-m-Y H:i');
        $rando2 = Carbon::now()
            ->diffForHumans(Carbon::parse('21-12-2024'));

        return "Mensaje 10pm Enviado | Enviado a las $rando. Falta para salir a vacaciones: $rando2";
    }
    public function enviarSoloAmi()
    {

        $dash = new dashboardController();
        $pendientes = $dash->GetPrestamosHoy();
        Mail::to("alejofg2@gmail.com")->send(new NewEnviarMail($pendientes));
        $rando = Carbon::now()->format('d-m-Y H:i');
        $rando2 = Carbon::now()->diffForHumans(Carbon::parse('21-12-2024'));
        return "Mensaje a fg2 10pm Enviado | Enviado a las $rando. Falta para salir a vacaciones: $rando2";
    }

    //web
    public function EnviarBitacoraManana(): string
    {

        $aunEsta = [];
        $dash = new dashboardController();

        //mande correo pero si esta entre las 5am y 7am
        if (!$dash->GetPrestamosAyer($aunEsta)) {
            $dash->WriteBitacoraAM($aunEsta);
            Mail::raw('En la bitacora se escribio que hay ' . count($aunEsta) . ' llaves pendientes aun.', function ($message) {
                $message->to('alejandro.madrid@colmayor.edu.co')
//                    ->cc('simon.pelaez@colmayor.edu.co')
                    ->subject('Mensaje enviado a las ' . Carbon::now()->format('d-m-Y H:i'));
            });
        } else {
            Mail::raw('En la bitacora no se escribe despues de las 7am', function ($message) {
                $message->to('alejandro.madrid@colmayor.edu.co')
//                    ->cc('simon.pelaez@colmayor.edu.co')
                    ->subject('Mensaje no se envio a la hora correcta');
            });
        }
        return "Mensaje mañanero Enviado";
    }
}
