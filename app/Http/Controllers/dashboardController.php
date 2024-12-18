<?php

namespace App\Http\Controllers;

use App\helpers\MyGlobalHelp;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\docentes;
use App\Models\Inspeccion;
use App\Models\prestamoHistorico;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class dashboardController extends Controller
{

    function getHorarios()
    {
        try {
            // Intenta obtener los datos de la base de datos secundaria
            $prestamosB = new PrestamosBothController();
            $prestamosB->GettingTablaSimon('horarios', 'Horario');
//            $horariosAqui = DB::table('horarios')->count();
//            if ($horariosAqui == 0) {
//
//                $horarios = DB::connection('secondary_db')->table('Horario')->get();
//
//                // Inserta los datos en la base de datos principal
//                foreach ($horarios as $horario) {
//                    DB::table('horarios')->insert([
//                        'id' => $horario->id,
//                        'docenteId' => $horario->docenteId,
//                        'aulaId' => $horario->aulaId,
//                        'horaInicio' => $horario->horaInicio,
//                        'horaFin' => $horario->horaFin,
//                        'dia' => $horario->dia,
//                        'semestre' => $horario->semestre,
//                    ]);
//                }
//                log::info("Horarios SI insertados");
//            }else{
//                log::info("Horarios no insertados");
//            }


        } catch (QueryException $e) {
            // Si falla la conexión, continúa sin interrumpir
            Log::error("No se pudo conectar a la base de datos secundaria: " . $e->getMessage());
        }
    }

    public function getTablaSimon($nombretablaAqui, $nombreTablaSimon)
    {
        DB::purge('secondary_db');
        $EntidadAqui = DB::table($nombretablaAqui)->count();
        if ($EntidadAqui == 0) {

            $laEntidadSimon = DB::connection('secondary_db')->table($nombreTablaSimon)->get();
            // Inserta los datos en la base de datos principal
            foreach ($laEntidadSimon as $entiSimon) {
                $data = (array)$entiSimon;
                if ($nombretablaAqui == 'prestamo') {
                    $data['simonid'] = $entiSimon['id'];
                }
                DB::table($nombretablaAqui)->insert($data);
            }
            log::info(" SI insertados");
        } else {
            log::info(" no insertados");
        }
    }

    public function WriteOnDataBaseBitacora($prestamos):void
    {
        $aulas = ($prestamos[0]);
        $StringBitacoraResumen = "";
        foreach ($aulas as $index => $aula) {
            $StringBitacoraResumen .= "Docente: $aula->docente_nombre ".
            " Aula: $aula->nombreAula ".
            " Fecha: $aula->fecha ".
            " Hora de Prestamo: $aula->horainicio a $aula->horafin ";
            if($aula->observaciones)
            $StringBitacoraResumen .= " Observaciones: $aula->observaciones ";
            if($aula->nombreArticulo)
            $StringBitacoraResumen .= " Artículo: $aula->nombreArticulo ";
        }

        DB::connection('secondary_db')->table('Bitacora')->insert([
            'fecha' => Carbon::now()->toIso8601String(), //tomemorize
            'nombre' => 'Alejandro Madrid',
            'cedula' => 1152194566,
            'observacion' => "Llaves pendientes: $StringBitacoraResumen",
            'estado' => 'INFORME',
        ]);

        log::info("se ha escrito en la bitacora");
    }

    function checkSecondaryDbConnection()
    {
        try {
            // Intentar una consulta simple para verificar la conexión
            DB::connection('secondary_db')->getPdo();
            return true;
        } catch (QueryException $e) {
            // Registrar el error o mostrar un mensaje si se requiere
            Log::error("No se pudo conectar a la base de datos secundaria: " . $e->getMessage());
            return false; // Retornar false para indicar que la conexión falló
        }
    }


    public function Dashboard($nombredoc = null)
    {
        $justTake = 3;
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' | dashboard antes de conectar a la bd2 | '));
        $prestamosB = new PrestamosBothController();
        $prestamosB->GettingTablaSimon('horarios', 'Horario');
        $prestamosB->GettingTablaSimon('docentes', 'Docente');

        $prestamosB->GettingTablaSimon('aula', 'Aula');
        $prestamosB->GettingTablaSimon('articulo', 'Articulo');
        $prestamosB->GettingTablaSimon('articuloprestamo', 'ArticuloPrestamo',1);
//            $articulos = DB::connection('secondary_db')->table('Articulo')->get();
//            $articuloPrestamos = DB::connection('secondary_db')->table('ArticuloPrestamo')->get();
//            $auditLogs = DB::connection('secondary_db')->table('AuditLog')->get();
//            $aulas = DB::connection('secondary_db')->table('Aula')->get();
//            $bitacoras = DB::connection('secondary_db')->table('Bitacora')->get();
        $docentesAqui = DB::table('docentes')->get()->take($justTake);
        $horariosAqui = DB::table('horarios')->get()->take($justTake);
        $prestamosAqui = DB::table('prestamo')->get();
        $AulaAqui = DB::table('aula')->get();
        $articuloprestamo = DB::table('articuloprestamo')->get();
//            $personal = DB::connection('secondary_db')->table('Personal')->get();
//            $prestamos = DB::connection('secondary_db')->table('Prestamo')->get();
//            $prestamosHistorico = DB::connection('secondary_db')->table('PrestamosHistorico')->get();
//            $solicitudesFast = DB::connection('secondary_db')->table('SolicitudesFast')->get();

        if ($nombredoc) {
            $diaHoyNombre = $this->obtenerDiaDeLaSemana();
            $Busqueda = DB::table('horarios as p')
                ->select('p.*', 'a.id as aulid', 'a.nombreAula', 'd.id as docid', 'd.nombre', 'd.tipousuario')
                ->leftJoin('aula as a', 'p.aulaId', '=', 'a.id')
                ->leftJoin('docentes as d', 'p.docenteId', '=', 'd.id')
                ->whereIn('p.docenteId', function ($query) use ($nombredoc) {
                    $query->select('id')
                        ->from('docentes')
                        ->where('nombre', 'LIKE', '%' . $nombredoc . '%');
                })
                ->where('p.semestre', '2024-2')
                ->where('p.dia', $diaHoyNombre)
                ->get();
        }

        $losQueFaltan= $this->GetPrestamosHoy()[0];
        $Obtenidos = [
            'horarios' => $horariosAqui,
            'docentes' => $docentesAqui,
            'prestamo' => $prestamosAqui,
            'AulaAqui' => $AulaAqui,
            'articuloprestamo' => $articuloprestamo,
            'losQueFaltan' => $losQueFaltan,
        ];

        return Inertia::render('Dashboard', [
            'users' => (int)User::count(),
            'roles' => (int)Role::count(),

            'rolesNameds' => Role::where('name', '<>', 'superadmin')->pluck('name'),

            'numberPermissions' => $numberPermissions,
            'Obtenidos' => $Obtenidos ?? ['no hay coneccion'],
            'Busqueda' => $Busqueda ?? '',
        ]);
    }

    function obtenerDiaDeLaSemana(): string
    {
        $dias = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ];

        $diaNumero = date('N') - 1; // 'N' devuelve el día de la semana (1 para lunes, 7 para domingo)

        return $dias[$diaNumero];
    }

    public function GetPrestamosHoy(): array
    {
        $prestamosB = new PrestamosBothController();
        $prestamosB->GettingTablaSimon('prestamo', 'Prestamo',1);
        $prestamosB->GettingTablaSimon('articuloprestamo', 'ArticuloPrestamo',1);
//        $laEntidadSimon = DB::connection('secondary_db')->table('ArticuloPrestamo')->get();

        $prestamos = DB::table('prestamo as p')
            ->select('p.id', 'p.docenteId', 'p.aulaId', 'p.fecha', 'p.horafin', 'p.horainicio', 'p.observaciones', 'd.nombre as docente_nombre', 'a.nombreAula', 'art.nombreArticulo')
            ->leftJoin('docentes as d', 'p.docenteId', '=', 'd.id')
            ->leftJoin('aula as a', 'p.aulaId', '=', 'a.id')
            ->leftJoin('articuloprestamo as ap', 'p.id', '=', 'ap.prestamoId')
            ->leftJoin('articulo as art', 'ap.articuloId', '=', 'art.id')
            ->orderBy('a.nombreAula', 'desc')
            ->get();

        $soloArticulos = DB::table('articuloprestamo as ap')->select('art.nombreArticulo')
//            ->leftJoin('prestamo as p', 'ap.articuloId', '=', 'art.id')
            ->leftJoin('articulo as art', 'art.id', '=', 'ap.articuloId')
            ->orderBy('art.nombreArticulo')
            ->get();

        $this->setIdPendientes($prestamos); //guarda los prestamos

        // Formatear las horas
        $prestamos->each(function ($prestamo) {
            $prestamo->horainicio = Carbon::createFromFormat('H:i', sprintf('%02d:%02d', floor($prestamo->horainicio / 1), $prestamo->horainicio % 1))->format('g:i A');
            $prestamo->horafin = Carbon::createFromFormat('H:i', sprintf('%02d:%02d', floor($prestamo->horafin / 1), $prestamo->horafin % 1))->format('g:i A');
        });
        return [$prestamos->toArray(),$soloArticulos->toArray()];
    }


    public function GetPrestamosAyer(&$aunEsta): bool
    {
        $prestamosB = new PrestamosBothController();
        $prestamosHoy = $prestamosB->GettingTablaSimonNoInsert('Prestamo');

        $prestamosAyer = DB::table('prestamo')->get();
        foreach ($prestamosAyer as $index => $Payer) {
            foreach ($prestamosHoy as $index2 => $Phoy) {
                if ($Payer->simonid == $Phoy->id) {
                    $aunEsta[$Payer->simonid] = $Payer;
                    break;
                }
            }
        }

        $horaActual = Carbon::now();
        $horaInicio = Carbon::today()->setTime(5, 0);  // 5:00 AM
        $horaFin = Carbon::today()->setTime(7, 0);     // 7:00 AM

        return $horaActual->between($horaInicio, $horaFin);
    }public function WriteBitacoraAM($aunEsta): void
    {
        $StringBitacoraResumen = "Llaves aun pendientes: ";
        foreach ($aunEsta as $index => $prestamo) {
            $prestamo->docente_nombre = docentes::find($prestamo->docenteId)->nombre;
            $prestamo->nombreAula = DB::table('aula')->Where('id',$prestamo->aulaId)->first()->nombreAula;

            $arti = DB::table('articuloprestamo')->Where('prestamoId',$prestamo->id)->first();
            $artiname = null;
            if($arti)
                $artiname = DB::table('articulo')->where('id',$arti->id)->first()->nombreArticulo;

            $StringBitacoraResumen .= "Docente: $prestamo->docente_nombre " .
                " Aula: $prestamo->nombreAula " .
                " Fecha: $prestamo->fecha " .
                " Hora de Prestamo: $prestamo->horainicio a $prestamo->horafin ";
            if ($prestamo->observaciones)
                $StringBitacoraResumen .= " Observaciones: $prestamo->observaciones ";
            if ($artiname)
                $StringBitacoraResumen .= " Artículo: $artiname ";
            $StringBitacoraResumen .= " -|- ";
        }

        DB::connection('secondary_db')->table('Bitacora')->insert([
            'fecha' => Carbon::now()->toIso8601String(), //tomemorize
            'nombre' => 'Alejandro Madrid',
            'cedula' => 1152194566,
            'observacion' => $StringBitacoraResumen,
            'estado' => 'INFORME',
        ]);

        log::info("se ha escrito en la bitacora");
    }

    private function setIdPendientes(Collection $prestamos): void //tableAqui prestamo
    {
        foreach ($prestamos as $index => $prestamo) {
            $data = (array)$prestamo;
            $data['simonid'] = $data['id'];
                // $data['fecha'] = Carbon::createFromFormat('Y-m-d H:i:s', $data['fecha']); //->format('Y-m-d')
                $data['fecha'] = Carbon::parse($data['fecha']);


            prestamoHistorico::insertOrIgnore($data);
        }
    }
}
