<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Horario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HorarioController extends Controller
{
    public $thisAtributos,$FromController = 'Horario';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create Horario', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read Horario', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update Horario', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete Horario', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new Horario())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$Horarios = Horario::with('no_nada');
        $Horarios = Horario::Where('id','>',0);
        return $Horarios;

    }
    public function Filtros(&$Horarios,$request){
        if ($request->has('search')) {
            $Horarios = $Horarios->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $Horarios = $Horarios->orderBy($request->field, $request->order);
        }else
            $Horarios = $Horarios->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' Horarios '));
        $Horarios = $this->Mapear();
        $this->Filtros($Horarios,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $Horarios->paginate($perPage),
            'total'                 => $Horarios->count(),

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
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:Horarios');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $Horario = Horario::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:Horarios EXITOSO', 'Horario id:' . $Horario->id . ' | ' . $Horario->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $Horario->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:Horarios');
        DB::beginTransaction();
        $Horario = Horario::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $Horario->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:Horarios EXITOSO', 'Horario id:' . $Horario->id . ' | ' . $Horario->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $Horario->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($Horarioid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:Horarios');
        $Horario = Horario::find($Horarioid);
        $elnombre = $Horario->nombre;
        $Horario->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:Horarios', 'Horario id:' . $Horario->id . ' | ' . $Horario->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $Horario = Horario::whereIn('id', $request->id);
        $Horario->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.Horario')]));
    }
    //FIN : STORE - UPDATE - DELETE

}
