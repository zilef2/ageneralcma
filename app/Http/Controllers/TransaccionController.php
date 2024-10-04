<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Comprobante;
use App\Models\concepto_flujo;
use App\Models\transaccion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransaccionController extends Controller
{
    public string $FromController = 'transaccion';
    public array $arrayBusque;
    public array $arrayFillableSearch;
    public array $thisAtributos;


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct()
    {
        $this->thisAtributos = (new transaccion())->getFillable();
        $this->thisAtributos = array_diff($this->thisAtributos, ['deleted_at']);
        $this->arrayBusque = [
            'search',
            'searchContrapartida',
            'searchConcepto',
        ];

        $this->arrayFillableSearch = [
            'codigo_cuenta_contable',
            'contrapartida_CI',
            'concepto_flujo_homologación',
        ];
    }

    private function Mapear($clase)
    {
        $Result = $clase->map(function ($clas) {
            $clas->cuenta = $clas->cuenta();
            return $clas;
        });

        return $Result;
    }

    public function BusquedasText($transaccions, $request)
    {
        foreach ($this->arrayBusque as $index => $busqueda) {
            $campo = $this->arrayFillableSearch[$index];
            if ($request->has($busqueda)) {
                $transaccions = $transaccions->where(function ($query) use ($request, $busqueda, $campo) {
                    $query->where($campo, 'LIKE', "%" . $request->{$busqueda} . "%");
                });
            }
        }
        return $transaccions->get();
    }

    public function Filtros($request)
    {
        $cacheKey = $this->generateCacheKey($request);

        // Cachear la búsqueda usando Cache::remember()
        return Cache::remember($cacheKey, 60, function () use ($request) {
            $transaccions = transaccion::Query();

            if ($request->has(['field', 'order'])) {
                $transaccions = $transaccions->orderBy($request->field, $request->order);
            } else {
                $transaccions = $transaccions->orderBy('updated_at', 'DESC');
            }

            // Realizar las búsquedas con filtros de texto
            return $this->BusquedasText($transaccions, $request);
        });
    }

    private function generateCacheKey($request)
    {
        $parts = [];

        if ($request->has('field')) {
            $parts[] = 'field_' . $request->field;
        }

        if ($request->has('order')) {
            $parts[] = 'order_' . $request->order;
        }

        foreach ($this->arrayBusque as $busqueda) {
            if ($request->has($busqueda)) {
                $parts[] = $busqueda . '_' . $request->{$busqueda};
            }
        }

        // Unir todas las partes con un delimitador único (puede ser un guión bajo, por ejemplo)
        return 'transaccions_search_' . implode('_', $parts);
    }

    //</editor-fold>

    public function index(Request $request)
    {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' index transaccions '));
        $laclase = $this->Mapear($this->Filtros($request));
//        $losSelect = $this->losSelect();

        $perPage = $request->has('perPage') ? $request->perPage : 1;
        $total = $laclase->count();
        $page = request('page', 1);
        $fromController = new LengthAwarePaginator(
            $laclase->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        $filters = ['search', 'field', 'order', 'searchContrapartida'];
        $filters = array_merge($this->arrayBusque, $filters);

        return Inertia::render($this->FromController . '/Index', [
            'fromController' => $fromController,
            'total' => $total,

            'breadcrumbs' => [['label' => __('app.label.' . $this->FromController), 'href' => route($this->FromController . '.index')]],
            'title' => __('app.label.' . $this->FromController),
            'filters' => $request->all($filters),
            'perPage' => (int)$perPage,

            'numberPermissions' => $numberPermissions,
            'thisAtributos' => array_values(array_diff($this->thisAtributos, [
                'nombre_cuenta',
                'nit',
            ])),
//            'losSelect'             => $losSelect,
        ]);
    }


    //<editor-fold desc="no index">
    public function create(){}

    //! STORE - UPDATE - DELETE
    //! STORE functions

    public function store(Request $request)
    {
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:transaccions');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $transaccion = transaccion::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:transaccions EXITOSO', 'transaccion id:' . $transaccion->id . ' | ' . $transaccion->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $transaccion->nombre]));
    }

    //fin store functions

    public function show($id){}

    public function edit($id){}

    public function update(Request $request, $id)
    {
        Myhelp::EscribirEnLog($this, ' Begin UPDATE:transaccions');
        DB::beginTransaction();
        $transaccion = transaccion::findOrFail($id);
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $transaccion->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:transaccions EXITOSO', 'transaccion id:' . $transaccion->id . ' | ' . $transaccion->nombre, false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $transaccion->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($transaccionid)
    {
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:transaccions');
        $transaccion = transaccion::find($transaccionid);
        $elnombre = $transaccion->nombre;
        $transaccion->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:transaccions', 'transaccion id:' . $transaccion->id . ' | ' . $transaccion->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request)
    {
        $transaccion = transaccion::whereIn('id', $request->id);
        $transaccion->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.transaccion')]));
    }

    //</editor-fold>


    public function Buscar_CP(Request $request)
    {
        try {
            DB::beginTransaction();
            $codigo = "CI";
            [$Transacciones, $valor_debito_credito] = $this->Paso1($codigo);

            $NumComprobantes = Comprobante::Where('codigo', $codigo)->count();
            if($NumComprobantes === 0)
                return back()->with('error', 'No hay comprobantes');

            foreach ($Transacciones as $index => $transa) {
                $comprobantes = Comprobante::Where('numero_documento', $transa->documento)
                    ->Where('codigo', $codigo);

                $HayComprobantes = clone $comprobantes;
                $HayComprobantes = $HayComprobantes->count();
                if($HayComprobantes === 0) continue;

//            dd($transa->documento, $comprobantes->get());
//            $princi = clone $comprobantes;
                $otros = clone $comprobantes;

                $principal = $comprobantes->where($valor_debito_credito, $transa->{$valor_debito_credito})->first();
//            $otross = $otros->where($valor_debito_credito,$transa->{$valor_debito_credito})->get();
                $otros = $otros->get()->reject(function ($item) use ($principal) {
                    return $item->id === $principal->id;
                });

                $otrosComprobantes = clone $otros;
                $ComprobantesCP = $otros;

                //va y busca los demas
                foreach ($ComprobantesCP as $item) {
                    $soloTieneUno = floor(intval($principal->valor_debito)) - floor(intval($item->valor_credito)) == 0; //todo: revisar si tiene mas de 1
                    $cuentaCP = $item->codigo_cuenta;

                    //buscamos el concepto
                    $concepto = $this->hallarConcepto($cuentaCP);
                    $transa->update([
                        'n_contrapartidas' => count($otrosComprobantes),
                        'contrapartida_CI' => $cuentaCP,
                        'concepto_flujo_homologación' => $concepto,
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('transaccion.index')->with('success',
                'Operación exitosa. '.$Transacciones->count().' transacciones '.$codigo.' del mes y año actual fueron revisadas'
            );
        } catch (\Throwable $th) {
            DB::rollback();
            $mensaj = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();
            return back()->with('error', 'Operacion con errores '.$mensaj);
        }
    }

    //FIN : STORE - UPDATE - DELETE
    private function hallarConcepto($cuentaCP)
    {
        $cf = concepto_flujo::Where('cuenta_contable', $cuentaCP)->first();
        if ($cf) {
            return $cf->concepto_flujo;
        }
        return 'No se encontró un concepto';
    }

    private function Paso1($codigo)
    {
        $valor_debito_credito = (strcmp($codigo, "CI") === 0) ? "valor_debito" : "valor_credito";
        $laFecha = new \DateTime();

        $mes = $laFecha->format('m'); // Obtiene el mes (en formato numérico)
        $mes = 8; // Obtiene el mes (en formato numérico)
        $anio = $laFecha->format('Y'); // Obtiene el año

        $Transacciones = transaccion::Where('codigo', $codigo)
            ->WhereYear('fecha_elaboracion', $anio)
            ->whereMonth('fecha_elaboracion', $mes)->get();
        //validar que tanto el Comprobante como la transsacion exista
//        dd($Transacciones[0]);
        return [$Transacciones, $valor_debito_credito];
    }

}
