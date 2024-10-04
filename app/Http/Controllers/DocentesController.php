<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\docentes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DocentesController extends Controller
{
    public $thisAtributos,$FromController = 'docentes';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create docentes', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read docentes', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update docentes', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete docentes', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new docentes())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$docentess = Docentes::with('no_nada');
        $docentess = Docentes::Where('id','>',0);
        return $docentess;

    }
    public function Filtros(&$docentess,$request){
        if ($request->has('search')) {
            $docentess = $docentess->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $docentess = $docentess->orderBy($request->field, $request->order);
        }else
            $docentess = $docentess->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' docentess '));
        $docentess = $this->Mapear();
        $this->Filtros($docentess,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $docentess->paginate($perPage),
            'total'                 => $docentess->count(),

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
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:docentess');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $docentes = docentes::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:docentess EXITOSO', 'docentes id:' . $docentes->id . ' | ' . $docentes->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $docentes->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:docentess');
        DB::beginTransaction();
        $docentes = docentes::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $docentes->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:docentess EXITOSO', 'docentes id:' . $docentes->id . ' | ' . $docentes->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $docentes->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($docentesid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:docentess');
        $docentes = docentes::find($docentesid);
        $elnombre = $docentes->nombre;
        $docentes->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:docentess', 'docentes id:' . $docentes->id . ' | ' . $docentes->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $docentes = docentes::whereIn('id', $request->id);
        $docentes->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.docentes')]));
    }
    //FIN : STORE - UPDATE - DELETE

}
