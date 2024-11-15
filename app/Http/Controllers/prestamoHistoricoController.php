<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\prestamoHistorico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class prestamoHistoricoController extends Controller
{
    public $thisAtributos,$FromController = 'prestamoHistorico';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create prestamoHistorico', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read prestamoHistorico', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update prestamoHistorico', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete prestamoHistorico', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new prestamoHistorico())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$prestamoHistoricos = prestamoHistorico::with('no_nada');
        $prestamoHistoricos = prestamoHistorico::Where('id','>',0);
        return $prestamoHistoricos;

    }
    public function Filtros(&$prestamoHistoricos,$request){
        if ($request->has('search')) {
            $prestamoHistoricos = $prestamoHistoricos->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $prestamoHistoricos = $prestamoHistoricos->orderBy($request->field, $request->order);
        }else
            $prestamoHistoricos = $prestamoHistoricos->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' prestamoHistoricos '));
        $prestamoHistoricos = $this->Mapear();
        $this->Filtros($prestamoHistoricos,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $prestamoHistoricos->paginate($perPage),
            'total'                 => $prestamoHistoricos->count(),

            'breadcrumbs'           => [['label' => __('app.label.'.$this->FromController), 'href' => route($this->FromController.'.index')]],
            'title'                 => __('app.label.'.$this->FromController),
            'filters'               => $request->all(['search', 'field', 'order']),
            'perPage'               => (int) $perPage,
            'numberPermissions'     => $numberPermissions,
//            'losSelect'             => $losSelect,
        ]);
    }

    public function create(){}

    //! STORE - UPDATE - DELETE
    //! STORE functions

    public function store(Request $request){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:prestamoHistoricos');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $prestamoHistorico = prestamoHistorico::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:prestamoHistoricos EXITOSO', 'prestamoHistorico id:' . $prestamoHistorico->id . ' | ' . $prestamoHistorico->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $prestamoHistorico->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:prestamoHistoricos');
        DB::beginTransaction();
        $prestamoHistorico = prestamoHistorico::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $prestamoHistorico->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:prestamoHistoricos EXITOSO', 'prestamoHistorico id:' . $prestamoHistorico->id . ' | ' . $prestamoHistorico->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $prestamoHistorico->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($prestamoHistoricoid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:prestamoHistoricos');
        $prestamoHistorico = prestamoHistorico::find($prestamoHistoricoid);
        $elnombre = $prestamoHistorico->nombre;
        $prestamoHistorico->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:prestamoHistoricos', 'prestamoHistorico id:' . $prestamoHistorico->id . ' | ' . $prestamoHistorico->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $prestamoHistorico = prestamoHistorico::whereIn('id', $request->id);
        $prestamoHistorico->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.prestamoHistorico')]));
    }
    //FIN : STORE - UPDATE - DELETE

}
