<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\PrestamosAyer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PrestamosAyerController extends Controller
{
    public $thisAtributos,$FromController = 'PrestamosAyer';


    //<editor-fold desc="Construc | mapea | filtro and losSelect">
    public function __construct() {
//        $this->middleware('permission:create PrestamosAyer', ['only' => ['create', 'store']]);
//        $this->middleware('permission:read PrestamosAyer', ['only' => ['index', 'show']]);
//        $this->middleware('permission:update PrestamosAyer', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete PrestamosAyer', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new PrestamosAyer())->getFillable(); //not using
    }


    public function Mapear(): Builder {
        //$PrestamosAyers = PrestamosAyer::with('no_nada');
        $PrestamosAyers = PrestamosAyer::Where('id','>',0);
        return $PrestamosAyers;

    }
    public function Filtros(&$PrestamosAyers,$request){
        if ($request->has('search')) {
            $PrestamosAyers = $PrestamosAyers->where(function ($query) use ($request) {
                $query->where('nombre', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
                    //                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
                ;
            });
        }

        if ($request->has(['field', 'order'])) {
            $PrestamosAyers = $PrestamosAyers->orderBy($request->field, $request->order);
        }else
            $PrestamosAyers = $PrestamosAyers->orderBy('updated_at', 'DESC');
    }
    public function losSelect()
    {
        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
        return $no_nadasSelect;
    }
    //</editor-fold>

    public function index(Request $request) {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' PrestamosAyers '));
        $PrestamosAyers = $this->Mapear();
        $this->Filtros($PrestamosAyers,$request);
//        $losSelect = $this->losSelect();


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render($this->FromController.'/Index', [
            'fromController'        => $PrestamosAyers->paginate($perPage),
            'total'                 => $PrestamosAyers->count(),

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
        $permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:PrestamosAyers');
        DB::beginTransaction();
//        $no_nada = $request->no_nada['id'];
//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $PrestamosAyer = PrestamosAyer::create($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'STORE:PrestamosAyers EXITOSO', 'PrestamosAyer id:' . $PrestamosAyer->id . ' | ' . $PrestamosAyer->nombre, false);
        return back()->with('success', __('app.label.created_successfully', ['name' => $PrestamosAyer->nombre]));
    }
    //fin store functions

    public function show($id){}public function edit($id){}

    public function update(Request $request, $id){
        $permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:PrestamosAyers');
        DB::beginTransaction();
        $PrestamosAyer = PrestamosAyer::findOrFail($id);
        $request->merge(['no_nada_id' => $request->no_nada['id']]);
        $PrestamosAyer->update($request->all());

        DB::commit();
        Myhelp::EscribirEnLog($this, 'UPDATE:PrestamosAyers EXITOSO', 'PrestamosAyer id:' . $PrestamosAyer->id . ' | ' . $PrestamosAyer->nombre , false);
        return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $PrestamosAyer->nombre]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($PrestamosAyerid){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:PrestamosAyers');
        $PrestamosAyer = PrestamosAyer::find($PrestamosAyerid);
        $elnombre = $PrestamosAyer->nombre;
        $PrestamosAyer->delete();
        Myhelp::EscribirEnLog($this, 'DELETE:PrestamosAyers', 'PrestamosAyer id:' . $PrestamosAyer->id . ' | ' . $PrestamosAyer->nombre . ' borrado', false);
        return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
    }

    public function destroyBulk(Request $request){
        $PrestamosAyer = PrestamosAyer::whereIn('id', $request->id);
        $PrestamosAyer->delete();
        return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.PrestamosAyer')]));
    }
    //FIN : STORE - UPDATE - DELETE

}
