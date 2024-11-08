<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\prestamoActual;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PrestamoActualController extends Controller
{
    public $thisAtributos,$FromController = 'prestamoActual';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create prestamoActual', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read prestamoActual', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update prestamoActual', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete prestamoActual', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new prestamoActual())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$prestamoActuals = PrestamoActual::with('no_nada');
        $prestamoActuals = PrestamoActual::Where('id','>',0);
        return $prestamoActuals;

    }
    public function Filtros(&$prestamoActuals,$request){
        if ($request->has('search')) {
            $prestamoActuals = $prestamoActuals->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $prestamoActuals = $prestamoActuals->orderBy($request->field, $request->order);
        }else
            $prestamoActuals = $prestamoActuals->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' prestamoActuals '));
        $prestamoActuals = $this->Mapear();
        $this->Filtros($prestamoActuals,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $prestamoActuals->paginate($perPage),
            'total'                 => $prestamoActuals->count(),

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
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:prestamoActuals');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $prestamoActual = prestamoActual::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:prestamoActuals EXITOSO', 'prestamoActual id:' . $prestamoActual->id . ' | ' . $prestamoActual->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $prestamoActual->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:prestamoActuals');
        DB::beginTransaction();
        $prestamoActual = prestamoActual::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $prestamoActual->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:prestamoActuals EXITOSO', 'prestamoActual id:' . $prestamoActual->id . ' | ' . $prestamoActual->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $prestamoActual->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($prestamoActualid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:prestamoActuals');
        $prestamoActual = prestamoActual::find($prestamoActualid);
        $elnombre = $prestamoActual->nombre;
        $prestamoActual->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:prestamoActuals', 'prestamoActual id:' . $prestamoActual->id . ' | ' . $prestamoActual->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $prestamoActual = prestamoActual::whereIn('id', $request->id);
        $prestamoActual->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.prestamoActual')]));
    }
    //FIN : STORE - UPDATE - DELETE

}