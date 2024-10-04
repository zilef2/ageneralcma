<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\llaves;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LlavesController extends Controller
{
    public $thisAtributos,$FromController = 'llaves';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create llaves', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read llaves', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update llaves', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete llaves', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new llaves())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$llavess = Llaves::with('no_nada');
        $llavess = Llaves::Where('id','>',0);
        return $llavess;

    }
    public function Filtros(&$llavess,$request){
        if ($request->has('search')) {
            $llavess = $llavess->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $llavess = $llavess->orderBy($request->field, $request->order);
        }else
            $llavess = $llavess->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' llavess '));
        $llavess = $this->Mapear();
        $this->Filtros($llavess,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $llavess->paginate($perPage),
            'total'                 => $llavess->count(),

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
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:llavess');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $llaves = llaves::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:llavess EXITOSO', 'llaves id:' . $llaves->id . ' | ' . $llaves->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $llaves->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:llavess');
        DB::beginTransaction();
        $llaves = llaves::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $llaves->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:llavess EXITOSO', 'llaves id:' . $llaves->id . ' | ' . $llaves->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $llaves->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($llavesid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:llavess');
        $llaves = llaves::find($llavesid);
        $elnombre = $llaves->nombre;
        $llaves->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:llavess', 'llaves id:' . $llaves->id . ' | ' . $llaves->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $llaves = llaves::whereIn('id', $request->id);
        $llaves->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.llaves')]));
    }
    //FIN : STORE - UPDATE - DELETE

}
