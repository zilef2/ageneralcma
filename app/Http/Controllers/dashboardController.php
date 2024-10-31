<?php

namespace App\Http\Controllers;

use App\helpers\MyGlobalHelp;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Inspeccion;
use App\Models\Role;
use App\Models\User;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class dashboardController extends Controller
{

    function getHorarios(){
        try {
            // Intenta obtener los datos de la base de datos secundaria
            DB::purge('secondary_db');
            $horarios = DB::connection('secondary_db')->table('Horario')->get();

            // Inserta los datos en la base de datos principal
            foreach ($horarios as $horario) {
                DB::table('horarios')->insert([
                    'id' => $horario->id,
                    'docenteId' => $horario->docenteId,
                    'aulaId' => $horario->aulaId,
                    'horaInicio' => $horario->horaInicio,
                    'horaFin' => $horario->horaFin,
                    'dia' => $horario->dia,
                    'semestre' => $horario->semestre,
                ]);
            }
        } catch (QueryException $e) {
            // Si falla la conexión, continúa sin interrumpir
            Log::error("No se pudo conectar a la base de datos secundaria: " . $e->getMessage());
        }
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


    public function Dashboard()
    {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' | dashboard antes de conectar a la bd2 | '));

        $this->getHorarios();
//            $articulos = DB::connection('secondary_db')->table('Articulo')->get();
//            $articuloPrestamos = DB::connection('secondary_db')->table('ArticuloPrestamo')->get();
//            $auditLogs = DB::connection('secondary_db')->table('AuditLog')->get();
//            $aulas = DB::connection('secondary_db')->table('Aula')->get();
//            $bitacoras = DB::connection('secondary_db')->table('Bitacora')->get();
//            $cuentas = DB::connection('secondary_db')->table('Cuenta')->get();
//            $docentes = DB::connection('secondary_db')->table('Docente')->get();
//            $funcionariosTecnologia = DB::connection('secondary_db')->table('FuncionariosTecnologia')->get();
//            $horarios = DB::connection('secondary_db')->table('Horario')->get();
//            $personal = DB::connection('secondary_db')->table('Personal')->get();
//            $prestamos = DB::connection('secondary_db')->table('Prestamo')->get();
//            $prestamosHistorico = DB::connection('secondary_db')->table('PrestamosHistorico')->get();
//            $solicitudesFast = DB::connection('secondary_db')->table('SolicitudesFast')->get();
        return Inertia::render('Dashboard', [
            'users' => (int)User::count(),
            'roles' => (int)Role::count(),

            'rolesNameds' => Role::where('name', '<>', 'superadmin')->pluck('name'),

            'numberPermissions' => $numberPermissions,
            'horarios' => $horarios ?? ['no hay coneccion'],
        ]);
    }
}
