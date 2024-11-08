<?php

namespace App\Http\Controllers;

use App\helpers\MyGlobalHelp;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Inspeccion;
use App\Models\prestamoActual;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class dashboardController extends Controller
{

    function getHorarios()
    {
        try {
            // Intenta obtener los datos de la base de datos secundaria
            $this->getTablaSimon('horarios', 'Horario');
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
                DB::table($nombretablaAqui)->insert($data);
            }
            log::info("Horarios SI insertados");
        } else {
            log::info("Horarios no insertados");
        }
    }

    public function getTablaSimonAndReplace($nombretablaAqui, $nombreTablaSimon)
    {
        DB::purge('secondary_db');
        $EntidadAqui = DB::table($nombretablaAqui)->count();

        $laEntidadSimon = DB::connection('secondary_db')->table($nombreTablaSimon)->get();
        DB::table($nombretablaAqui)->delete();
        // Inserta los datos en la base de datos principal
        foreach ($laEntidadSimon as $entiSimon) {
            $data = (array)$entiSimon;
            DB::table($nombretablaAqui)->insert($data);
        }
        log::info("$nombretablaAqui SI insertados");

    }

    function checkSecondaryDbConnection()
    {
        try {
            // Intentar una consulta simple para verificar la conexión
            DB::connection('secondary_db')->getPdo();
            return true;
        } catch (QueryException $e) {
            // Registrar el error o mostrar un mensaje si se requiere
            \Log::error("No se pudo conectar a la base de datos secundaria: " . $e->getMessage());
            return false; // Retornar false para indicar que la conexión falló
        }
    }


    public function Dashboard($nombredoc = null)
    {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' | dashboard antes de conectar a la bd2 | '));
        $this->getTablaSimon('horarios', 'Horario');
        $this->getTablaSimon('docentes', 'Docente');
//        $this->getTablaSimonAndReplace('prestamo', 'Prestamo');
        $this->getTablaSimon('aula', 'Aula');
        $this->getTablaSimon('articulo', 'Articulo');
        $this->getTablaSimonAndReplace('articuloprestamo', 'ArticuloPrestamo');

//            $articulos = DB::connection('secondary_db')->table('Articulo')->get();
//            $articuloPrestamos = DB::connection('secondary_db')->table('ArticuloPrestamo')->get();
//            $auditLogs = DB::connection('secondary_db')->table('AuditLog')->get();
//            $aulas = DB::connection('secondary_db')->table('Aula')->get();
//            $bitacoras = DB::connection('secondary_db')->table('Bitacora')->get();
//            $cuentas = DB::connection('secondary_db')->table('Cuenta')->get();
        $docentes = DB::connection('secondary_db')->table('Docente')->get()->take(3);
        $docentesAqui = DB::table('docentes')->get()->take(3);
//            $funcionariosTecnologia = DB::connection('secondary_db')->table('FuncionariosTecnologia')->get();
        $horarios = DB::connection('secondary_db')->table('Horario')->get()->take(3);
        $horariosAqui = DB::table('horarios')->get()->take(3);
        $prestamosAqui = DB::table('prestamo')->get();
        $AulaAqui = DB::table('aula')->get();
        $articuloprestamo = DB::table('articuloprestamo')->get();
//            $personal = DB::connection('secondary_db')->table('Personal')->get();
//            $prestamos = DB::connection('secondary_db')->table('Prestamo')->get();
//            $prestamosHistorico = DB::connection('secondary_db')->table('PrestamosHistorico')->get();
//            $solicitudesFast = DB::connection('secondary_db')->table('SolicitudesFast')->get();
//        $Obtenidos = [
//            'horarios' => $horarios,
//            'docentes' => $docentes,
//        ];

//        foreach ($prestamosAqui as $item) {
//            $data = array_filter((array)$item, function ($value) {
//                return !is_null($value);
//            });
//            if (count($data) > 9) {
//                if (!isset($data['fecha'])) {
//                    $data['fecha'] = '2024-01-01'; // Usa una fecha predeterminada adecuada
//                }
//                prestamoActual::create($data);
//            }
//        }

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

        $losQueFaltan = $this->GetPrestamosHoy();
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

    public function GetPrestamosHoy(): Collection
    {
        $this->getTablaSimonAndReplace('prestamo', 'Prestamo');
        $prestamos = DB::table('prestamo as p')
            ->select('p.docenteId', 'p.aulaId', 'p.fecha', 'p.horafin', 'p.horainicio', 'p.observaciones', 'd.nombre as docente_nombre', 'a.nombreAula')
            ->join('docentes as d', 'p.docenteId', '=', 'd.id')
            ->join('aula as a', 'p.aulaId', '=', 'a.id')
            ->orderBy('a.nombreAula', 'desc')
            ->get();

        // Formatear las horas
        $prestamos->each(function ($prestamo) {
            $prestamo->horainicio = Carbon::createFromFormat('H:i', sprintf('%02d:%02d', floor($prestamo->horainicio / 1), $prestamo->horainicio % 1))->format('g:i A');
            $prestamo->horafin = Carbon::createFromFormat('H:i', sprintf('%02d:%02d', floor($prestamo->horafin / 1), $prestamo->horafin % 1))->format('g:i A');
        });
        return $prestamos;
    }
}
