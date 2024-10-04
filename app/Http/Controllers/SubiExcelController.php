<?php

namespace App\Http\Controllers;

use App\helpers\HelpExcel;
use App\helpers\Myhelp;
use App\Imports\BancoImport;
use App\Imports\ComprobanteImport;
use App\Imports\CuentaImport;
use App\Imports\TransaccionesImport;
use App\Models\cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SubiExcelController extends Controller
{

    public function subirexceles()
    { //just  a view
        Myhelp::EscribirEnLog($this, ' ingreso a la vista subir excel');

        return Inertia::render('Excel/subirExceles', [
            'title' => __('app.label.user'),
            'numUsuarios' => (cuenta::Where('id','>',0)->count()),
        ]);
    }

    // Duplicate entry '1152194566' for key 'users_identificacion_unique'
    private function MensajeWar($personalImp)
    {
        $bandera = false;
        $contares = [
            'contarVacios',
            'contarTotalIncongruente',
            'contarIncongruencias',
            'contarUsuariosInexistentes',
        ];
        $mensajesWarnings = [
            '# Filas con celdas vacias: ',
            '# Hay Filas: total con incongruencias: ',
            '# Incongruencias: ',
            '# Usuarios Inexistentes: ',
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
                    $mensaje .= $value . '<b>'. ${$contares[$key]} . '</b>.<br><br> '. $personalImp->{$NombreVariable}.'<br> ';
                }
            }
        }

        return $mensaje;
    }

    public function upExCuentas(Request $request){
        Myhelp::EscribirEnLog($this, get_called_class(), 'importando', false);
        $countfilas = 0;
        try {
            DB::beginTransaction();
            if ($request->archivo1) {

                $helpExcel = new HelpExcel();
                $mensageWarning = $helpExcel->validarArchivoExcel($request,'archivo1');
                if ($mensageWarning != ''){
                    DB::rollback();
                    return back()->with('warning', $mensageWarning);
                }

                $personalImp = new CuentaImport();
                Excel::import($personalImp, $request->archivo1);

                $countfilas = $personalImp->ContarFilasAbsolutas;

                $MensajeWarning = HelpExcel::MensajeWarComprobante($personalImp);

                if ($MensajeWarning !== '') { //exito

                    return back()->with('success', 'Formularios nuevos: ' . $countfilas)
                        ->with('warning2', $MensajeWarning);
                }

                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' finalizo con exito', false);
                DB::commit();
                if ($countfilas == 0){
                    return back()->with('success', __('app.label.op_successfully') . ' No hubo cambios');
                } else{
//                    cuenta::where('user_id', $personalImp->usuario->id)->update(['enviado' => 1]);

                    return back()->with('success', __('app.label.op_successfully') . ' Se leyeron ' . $countfilas . ' filas con exito');
                }

            } else {
                DB::rollback();
                return back()->with('error', __('app.label.op_not_successfully') . ' Archivo no seleccionado');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $lasession = session('larow') ?? 'error de session';
            $lasession = $lasession[0] ?? 'error de session';
            $mensajeError = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();

            Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: ' . $mensajeError, false);
            return back()->with('error', __('app.label.op_not_successfully') . ' Usuario del error: ' . $lasession . ' error en la iteracion ' . $countfilas . ' ' .$mensajeError);
        }
    }


    public function upExTransacciones(Request $request){
        Myhelp::EscribirEnLog($this, get_called_class(), 'importando', false);
        $countfilas = 0;
        $entidad = 'Transaccion';
        try {
            DB::beginTransaction();
            if ($request->archivo2) {

                $helpExcel = new HelpExcel();
                $mensageWarning = $helpExcel->validarArchivoExcel($request,'archivo2');
                if ($mensageWarning != ''){
                    DB::rollback();
                    return back()->with('warning', $mensageWarning);
                }

                $personalImp = new TransaccionesImport();
                Excel::import($personalImp, $request->archivo2);

                $countfilas = $personalImp->ContarFilasAbsolutas;

                $MensajeWarning = HelpExcel::MensajeWarComprobante($personalImp);

                if ($MensajeWarning !== '') { //exito

                    return back()->with('success', $entidad.'es nuevas: ' . $countfilas)
                        ->with('warning2', $MensajeWarning);
                }

                Myhelp::EscribirEnLog($this, 'IMPORT:'.$entidad, ' finalizo con exito', false);
                DB::commit();
                if ($countfilas == 0){
                    return back()->with('success', __('app.label.op_successfully') . ' No hubo cambios');
                } else{
                    return back()->with('success', __('app.label.op_successfully') . ' Se leyeron ' . $countfilas . ' filas con exito');
                }

            } else {
                DB::rollback();
                return back()->with('error', __('app.label.op_not_successfully') . ' Archivo no seleccionado');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $lasession = session('larow') ?? 'error de session';
            $lasession = $lasession[0] ?? 'error de session';
            $mensajeError = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();

            Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: ' . $mensajeError, false);
            if(str_starts_with($th->getMessage(),'|')){
                return back()->with('warning',$mensajeError);
            }else{
                return back()->with(
                    'error', __('app.label.op_not_successfully')
                    . ' Comprobante del error: ' . $lasession
                    . ' error en la iteracion ' . $countfilas . ' ' .$mensajeError
                );
            }
        }
    }


    public function uploadFileComprobantes(Request $request){
        $archivin = "archivo3";
        Myhelp::EscribirEnLog($this, get_called_class(), 'importando comprobantes ', false);
        $countfilas = 0;
        try {
            DB::beginTransaction();
            if ($request->{$archivin}) {

                $helpExcel = new HelpExcel();
                $mensageWarning = $helpExcel->validarArchivoExcel($request,$archivin);
                if ($mensageWarning != ''){
                    DB::rollback();
                    return back()->with('warning', $mensageWarning);
                }

                $personalImp = new ComprobanteImport();
                Excel::import($personalImp, $request->{$archivin});

                $countfilas = $personalImp->ContarFilasAbsolutas;

                $MensajeWarning = HelpExcel::MensajeWarComprobante($personalImp);
                if ($MensajeWarning !== '') { //exito

                    return back()->with('success', 'Formularios nuevos: ' . $countfilas)
                        ->with('warning2', $MensajeWarning);
                }

                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' finalizo con exito', false);
                DB::commit();
                if ($countfilas == 0){
                    return back()->with('success', __('app.label.op_successfully') . ' No hubo cambios');
                } else{
//                    cuenta::where('user_id', $personalImp->usuario->id)->update(['enviado' => 1]);

                    return back()->with('success', __('app.label.op_successfully') . ' Se leyeron ' . $countfilas . ' filas con exito');
                }

            } else {
                DB::rollback();
                return back()->with('error', __('app.label.op_not_successfully') . ' Archivo no seleccionado');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $lasession = session('larow') ?? 'error de session';
            $lasession = $lasession[0] ?? 'error de session';

            if(str_starts_with($th->getMessage(),'|')){
                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: '
                    . $th->getMessage(), false);
                return back()->with('warning',$th->getMessage());

            }else{

                $mensajeError = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();
                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: '
                    . $mensajeError, false);
                return back()->with(
                    'error', __('app.label.op_not_successfully')
                    . ' Comprobante del error: ' . $lasession
                    . ' error en la iteracion ' . $countfilas . ' ' .$mensajeError
                );
            }
        }
    }
    public function uploadFileBancos(Request $request){
        $archivin = "archivo4";
        Myhelp::EscribirEnLog($this, get_called_class(), 'importando bancos ', false);
        $countfilas = 0;
        try {
            DB::beginTransaction();
            if ($request->{$archivin}) {

                $helpExcel = new HelpExcel();
                $mensageWarning = $helpExcel->validarArchivoExcel($request,$archivin);
                if ($mensageWarning != ''){
                    DB::rollback();
                    return back()->with('warning', $mensageWarning);
                }

                $elImport = new BancoImport();
                Excel::import($elImport, $request->{$archivin});

                $countfilas = $elImport->ContarFilas;

                $MensajeWarning = HelpExcel::MensajeWarSoloVacios($elImport);
                if ($MensajeWarning !== '') { //exito
                    return back()->with('success', 'Formularios nuevos: ' . $countfilas)
                        ->with('warning2', $MensajeWarning);
                }

                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' finalizo con exito', false);
                DB::commit();
                if ($countfilas == 0){
                    return back()->with('success', __('app.label.op_successfully') . ' No hubo cambios');
                } else{
//                    cuenta::where('user_id', $personalImp->usuario->id)->update(['enviado' => 1]);
                    return back()->with('success', __('app.label.op_successfully') . ' Se leyeron ' . $countfilas . ' filas con exito');
                }
            } else {
                DB::rollback();
                return back()->with('error', __('app.label.op_not_successfully') . ' Archivo no seleccionado');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $lasession = session('larow') ?? 'error de session';
            $lasession = $lasession[0] ?? 'error de session';

            if(str_starts_with($th->getMessage(),'|')){
                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: '
                    . $th->getMessage(), false);
                return back()->with('warning',$th->getMessage());

            }else{

                $mensajeError = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();
                Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo importacion: '
                    . $mensajeError, false);
                return back()->with(
                    'error', __('app.label.op_not_successfully')
                    . ' Comprobante del error: ' . $lasession
                    . ' error en la iteracion ' . $countfilas . ' ' .$mensajeError
                );
            }
        }
    }

}
