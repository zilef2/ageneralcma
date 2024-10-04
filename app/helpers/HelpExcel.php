<?php

namespace App\helpers;

use DateTime;

// use Hamcrest\Type\IsInteger;

class HelpExcel
{

    public static function getFechaExcel($lafecha, $inDate = false)
    {
        //the date fix
        if (is_numeric($lafecha)) { //toproof
            $unixDate = ($lafecha - 25568) * 86400;
            $formatoDefinido = 'd/m/Y';
            // $unixDate = ($lafecha - 25569) * 86400;
//            $readableDate = date('Y/m/d', $unixDate);
            $readableDate = date($formatoDefinido, $unixDate);
            $fechaReturn = DateTime::createFromFormat($formatoDefinido, $readableDate);
            if ($fechaReturn === false) {
                 throw new \Exception('Fecha inválida '.$lafecha. ' --++--');
            }
        } else {
            $fechaReturn = DateTime::createFromFormat('d/m/Y h:i:s a', $lafecha);
//            $fechaReturn = DateTime::createFromFormat('Y/m/d', $lafecha);
            if ($fechaReturn === false) {
                $fechaReturn = DateTime::createFromFormat('d/m/Y', $lafecha);
                if ($fechaReturn === false) {
                    throw new \Exception(' Fecha inválida opcion B ' . $lafecha);
                }
            }
        }

        if ($inDate) {
            return $fechaReturn->format('Y-m-d');
        } else return $fechaReturn;
    }

    public function validarArchivoExcel($request, $nombreArchivo)
    {
        $exten = $request->{$nombreArchivo}->getClientOriginalExtension();
        if ($exten != 'xlsx' && $exten != 'xls') {
            return 'El archivo debe ser de Excel';
        }
        $megas = 12;
        $pesoKilobyte = ((int)($request->{$nombreArchivo}->getSize())) / (1024);
        if ($pesoKilobyte > ($megas * 1024)) { //debe pesar menos de 12MB
            return 'El archivo debe pesar menos de '.$megas.'MB';
        }
        return '';
    }


    public static function MensajeWarComprobante($personalImp): string
    {
        $bandera = false;
        $contares = [
            'contarVacios',
        ];
        $mensajesWarnings = [
            '# Filas con celdas vacias: ',
        ];


        foreach ($contares as $key => $value) {
            $$value = $personalImp->{$value};
            $bandera = $bandera || $$value > 0;
        }

        $mensaje = '';
        if ($bandera) {
            foreach ($mensajesWarnings as $key => $value) {
                if (${$contares[$key]} > 0) {
                    $NombreVariable = $contares[$key] . 'string';
                    $mensaje .= $value . '<b>' . ${$contares[$key]} . '</b>.<br><br> ' . $personalImp->{$NombreVariable} . '<br> ';
                }
            }
        }

        return $mensaje;
    }
    public static function MensajeWarSoloVacios($personalImp): string
    {
        $bandera = false;
        $contares = [
            'contarVacios',
        ];
        $mensajesWarnings = [
            '# Filas con celdas vacias: ',
        ];


        foreach ($contares as $key => $value) {
            $$value = $personalImp->{$value};
            $bandera = $bandera || $$value > 0;
        }

        $mensaje = '';
        if ($bandera) {
            foreach ($mensajesWarnings as $key => $value) {
                if (${$contares[$key]} > 0) {
                    $NombreVariable = $contares[$key] . 'string';
                    $mensaje .= $value . '<b>' . ${$contares[$key]} . '</b>.<br><br> ' . $personalImp->{$NombreVariable} . '<br> ';
                }
            }
        }

        return $mensaje;
    }
}
