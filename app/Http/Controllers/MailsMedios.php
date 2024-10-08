<?php

namespace App\Http\Controllers;

use App\Mail\EstadoMedios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailsMedios extends Controller
{
    public function principal()
    {

        $infoGigante = $this->infoGigante;
        preg_match_all('/Salón: ([A-Z0-9]+)/', $infoGigante, $matches);
        $salones = implode(', ', $matches[1]);
        if (strcmp('', $salones) === 0) {
            $salones = 'No hay salones pendientes';
        }
        
        
        preg_match_all('/HDMI([0-9]+)/', $infoGigante, $matches);
        $HDMI = implode(', ', $matches[1]);
        if (strcmp('', $HDMI) === 0) {
            $HDMI = 'No hay HDMI pendientes';
        }

        preg_match_all('/P([0-9]+)/', $infoGigante, $matches);
        $computador = implode(', ', $matches[1]);
        if (strcmp('', $computador) === 0) {
            $computador = 'No hay computadores pendientes';
        }


//        preg_match_all('/Fin: \d+\s*(.*)/', $infoGigante, $matches);
        preg_match_all('/Fin: \d+\s+➕Elementos prestados:\s+(.*?)(\n|🗑️)/s', $infoGigante, $matches);

        //<editor-fold desc="extraer fecha">
        // Obtener la fecha de hoy en el mismo formato
        $hoy = date('Y-m-d');
        preg_match_all('/Usuario:\s*(.+?)\s*[\r\n]+.*?Fecha:\s*(\w+ \d{1,2} de \w+ del \d{4} \d{2}:\d{2})/s', $infoGigante, $UserMatches, PREG_SET_ORDER);
        $demoras = '';
        foreach ($UserMatches as $userMatch) {
            $usuario = trim($userMatch[1]);
            $fecha2 = trim($userMatch[2]);

            $fechaLimpia = trim(preg_replace('/^(lunes|martes|miércoles|jueves|viernes|sábado|domingo)\s+|\s*del\s*/i', ' ', $fecha2));

            $mesesEn = [
                'enero' => 'January',
                'febrero' => 'February',
                'marzo' => 'March',
                'abril' => 'April',
                'mayo' => 'May',
                'junio' => 'June',
                'julio' => 'July',
                'agosto' => 'August',
                'septiembre' => 'September',
                'octubre' => 'October',
                'noviembre' => 'November',
                'diciembre' => 'December'
            ];
            $fechaLimpia = str_replace(' de ', ' ', $fechaLimpia);
            
            $fechaLimpia = strtr($fechaLimpia, $mesesEn);
            $fecha = \DateTime::createFromFormat('j F Y H:i', $fechaLimpia);
            if (!$fecha) {
                dd(
                    "Error en la conversión de la fecha\n",
                    print_r(\DateTime::getLastErrors())
                ); 
            }

            $StringFecha = '';
            if ($fecha) {
                $StringFecha = $fecha->format('Y-m-d'); // Formato estándar de MySQL
            }

            if ($StringFecha !== $hoy) {
                $demoras .= "La persona $usuario se ha demorado con el prestamo que genero el $StringFecha\n\n";
            }
        }
        //</editor-fold>


        echo '<br>Llaves pendientes: '.$salones . "\n";
        echo '<br>Computadores: ' . $computador . "\n\n";
        echo '<br>HDMI: ' . $HDMI . "\n\n";
        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $observaciones[] = trim($match) . PHP_EOL;
            }
        } else {
            $observaciones[0] = "Sin observaciones adicionales";
        }

        $Observaciones = implode(',', $observaciones);
        echo '<br>Observaciones: ' . $Observaciones . "\n\n";


        if (strcmp($demoras, '') !== 0) {
            $demoras = 'Incumplimientos: \n' . $demoras . "\n\n";
            echo '<br> $demoras';
        }

        Mail::
//        to('alejandro.madrid@colmayor.edu.co')
        to('tecnologia@colmayor.edu.co')
//            ->cc('viceadministrativa@colmayor.edu.co')
//            ->cc('simon.pelaez@colmayor.edu.co')
            ->send(new EstadoMedios([
                'observaString' => $Observaciones,
                'computador' => $computador,
                'HDMI' => $HDMI,
                'salones' => $salones,
                'demoras' => $demoras,
            ]));

        echo '<br> <br>Email enviado';
    }

    public $infoGigante = "
 Préstamo generado:
Salón: A181

Usuario: Adriana Maria Ocampo Chalarca

Cédula: 43098998

Dependencia: Facultad De Ciencias De La Salud

Prestó: Practicante

Fecha:lunes 7 de octubre del 2024 19:35

Inicio: 20

Fin: 22

Elementos prestados:
➕
Llave 1

🗑️
P39645
🗑️
Devolver todo
Préstamo generado:
Salón: C106

Usuario: Natalia Maria Posada Perez

Cédula: 1020420652

Dependencia: Facultad De Ciencias Sociales Y Educacion

Prestó: Practicante

Fecha:lunes 7 de octubre del 2024 18:11

Inicio: 18

Fin: 21

Elementos prestados:
➕
Llave 1

🗑️
Devolver todo
Préstamo generado:
Salón: C104

Usuario: Carlos Mario Trujillo Torres

Cédula: 70877249

Dependencia: Facultad De Ciencias De La Salud

Prestó: Practicante

Fecha:lunes 7 de octubre del 2024 18:07

Inicio: 18

Fin: 22

Elementos prestados:
➕
Llave 1

";
}
