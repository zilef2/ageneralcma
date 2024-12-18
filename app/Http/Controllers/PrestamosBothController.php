<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestamosBothController extends Controller
{
    public function GettingTablaSimon($nombretablaAqui, $nombreTablaSimon,$deleteAll = false)
    {
        DB::purge('secondary_db');
//        $EntidadAqui = DB::table($nombretablaAqui)->count();

        $laEntidadSimon = DB::connection('secondary_db')->table($nombreTablaSimon)->get();
        if($deleteAll) DB::table($nombretablaAqui)->delete();
        // Inserta los datos en la base de datos principal
        foreach ($laEntidadSimon as $entiSimon) {
            $data = (array)$entiSimon;
            if ($nombretablaAqui == 'prestamo' || $nombretablaAqui == 'horarios' || $nombretablaAqui == 'docentes' || $nombretablaAqui == 'personal') {
                $data['simonid'] = $entiSimon->id;
            }
            DB::table($nombretablaAqui)->insertOrIgnore($data);
        }
        log::info("$nombretablaAqui SI insertados");
    }

    public function GettingTablaSimonNoInsert($nombreTablaSimon)
    {
//        DB::purge('secondary_db');
        return DB::connection('secondary_db')->table($nombreTablaSimon)->get();
    }
}
