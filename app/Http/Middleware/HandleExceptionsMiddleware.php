<?php

namespace App\Http\Middleware;

use App\helpers\Myhelp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class HandleExceptionsMiddleware{
   
    public function handle(Request $request, Closure $next)
    {
//        DB::beginTransaction();
//        try {
//            $response = $next($request);
//            if (isset($response->exception) && $response->exception) {
//                throw $response->exception;
//            }
//            DB::commit();
//            return $response;
//        } catch (\Throwable $th) {
//            DB::rollback();
//            if (!($th instanceof QueryException || str_contains($th->getFile(), 'Http/Controllers'))) {
//                throw $th;
//            }
//            // Obtiene información detallada del error
//            $mensan = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();
//            $trace = $th->getTraceAsString();
//
//            // Filtra el rastro para eliminar líneas específicas
//            $filteredTrace = implode("\n", array_filter(explode("\n", $trace), function ($line) {
//                return !str_contains($line, 'vendor');
//            }));
//
//            Myhelp::EscribirEnLog($this, 
//                ' MiddleOperation ', ' fallo | ' . $mensan . ' Trace: ' . $filteredTrace, false);
//
//            $myhelp = new Myhelp();
//            if (app()->environment('production')) {
//                // Manejo en ambiente de producción
//                return response()->json(['error' => 'Ocurrió un error en el servidor.'], 500);
//            } elseif (app()->environment('testing') || app()->environment('local')) {
//                return response()->json([
//                    'error2' => $myhelp->cortarFrase($mensan, 1),
//                    'error1' => $myhelp->cortarFrase($mensan, 2),
//                    'error' => $mensan,
//                    'trace' => $filteredTrace
//                ], 500);
//            }
//            return back()->with('error', __('app.label.created_error', ['name' => 'generico ']) . $mensan . $myhelp->cortarFrase($mensan, 21));
//        }
    }
}
