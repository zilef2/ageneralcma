<?php

namespace App\Http\Controllers;

use App\Exports\MultipleExport;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class UserController extends Controller {
    public $thisAtributos;
    public $funcionalidades;

    public function __construct() {
        $this->funcionalidades =[
          'firma' => 0,
          'tipo_user' => 0,
        ];
        $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:read user', ['only' => ['index', 'show']]);
        $this->middleware('permission:update user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy', 'destroyBulk']]);
        $this->thisAtributos = (new User())->getFillable(); //not using
    }

    public function downloadAnexos()
    {
        $zip = new ZipArchive;
        $fileName = 'anexos.zip';
        $filePath = storage_path('app/public/' . $fileName);
        $sourcePath = storage_path('app/public/anexosPrimerForm');

        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::files($sourcePath);

            foreach ($files as $file) {
                $relativeName = basename($file);
                if (!preg_match('/\.git$|\.gitconfig$/', $relativeName)) {
                    $zip->addFile($file, $relativeName);
                }
            //                $zip->addFile($file, $relativeName);
            }
            $zip->close();
        }

        session()->flash('message', ' Archivos descargados.');
                //        return back()->download($filePath)->deleteFileAfterSend(true);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function SelectsMasivos($numberPermissions) {
        $tiposResponsable = DB::table('tipo_users')->pluck('nombre')->toArray();

        return $tiposResponsable;
    }

    public function MapearClasePP(&$users, $numberPermissions,$request,&$roles){
            //        $role = auth()->user()->roles->pluck('name')[0];
        if ($numberPermissions < 10000) {
            $roles = Role::where('name', '<>', 'superadmin')->where('name', '<>', 'admin')->get();
            $users->whereHas('roles', function ($query) {
                return $query->whereNotIn('name', ['superadmin', 'admin'])->orderBy('name');
            });
        } else {
            $users->whereHas('roles', function ($query) {
                return $query->orderBy('name');
            });
            $roles = Role::get();
        }

            //            dd($request->field, $request->has(['field', 'order']));
        if ($request->has(['field', 'order'])) {
            $users = $users->orderBy($request->field, $request->order);

        } else {
            $users = $users->orderBy('updated_at', 'desc');
        }

        if ($request->has('search')) {
            $users->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('email', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%");
            })->where('name', '!=', 'admin')->where('name', '!=', 'Superadmin');
            // $users->where('name', 'LIKE', "%" . $request->search . "%");
        }

        $users = $users->get()->map(function ($user) {
            return $user;
        })->filter();
    }


    public function index(Request $request) {
        $permissions = Myhelp::EscribirEnLog($this, ' users');
        $numberPermissions = MyModels::getPermissionToNumber($permissions);

        $users = User::query()->with('roles');
        $this->MapearClasePP($users,$numberPermissions,$request,$roles);


        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $total = $users->count();
        $page = request('page', 1); // Current page number
        $fromController =  new LengthAwarePaginator($users->forPage($page, $perPage), $total, $perPage, $page, ['path' => request()->url()]);

        return Inertia::render('User/Index', [
            'breadcrumbs'           => [['label' => __('app.label.user'), 'href' => route('user.index')]],
            'title'                 => __('app.label.user'),
            'filters'               => $request->all(['search', 'field', 'order']),
            'perPage'               => (int) $perPage,
            'users'                 => $fromController,
            'funcionalidades'       => $this->funcionalidades,
            'roles'                 => $roles,
            'numberPermissions'     => $numberPermissions,
            'losSelect'             => $this->SelectsMasivos($numberPermissions),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){}

    //! STORE - UPDATE - DELETE
    public function store(UserStoreRequest $request){
        $permissions = Myhelp::EscribirEnLog($this, 'STORE:users');
        if(isset($request->sexo['value'])){
            $sexo = is_string($request->sexo) ? $request->sexo : $request->sexo['value'];
        }else{
            $sexo = 'Masculino';
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password' => Hash::make($request->identificacion . '+-'),

            'identificacion' => $request->identificacion,
            'sexo' => $sexo,
            'fecha_nacimiento' => Myhelp::updatingDate($request->fecha_nacimiento),
            'celular' => $request->celular,

            'cargo'     => $request->cargo,
            'tipo_user'     => $request->tipo_user,
            'firma'     => $request->firma,
        ]);
        $user->assignRole($request->role);
        Myhelp::EscribirEnLog($this, 'STORE:users', 'usuario id:' . $user->id . ' | ' . $user->name . ' guardado', false);

        return back()->with('success', __('app.label.created_successfully', ['name' => $user->name]));
    }
    //fin store functions
    public function show($id){}public function edit($id){}

    public function update(UserUpdateRequest $request, $id){
        Myhelp::EscribirEnLog($this, 'UPDATE:users', '', false);

        $sexo = is_string($request->sexo) ? $request->sexo : $request->sexo['value'];
        $user = User::findOrFail($id);
        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,

            'identificacion' => $request->identificacion,
            'sexo' => $sexo,
            'fecha_nacimiento' => Myhelp::updatingDate($request->fecha_nacimiento),
            'celular' => $request->celular,

            'cargo'     => $request->cargo,
            'tipo_user'     => $request->tipo_user,
            'firma'     => $request->firma,
        ]);

        $user->syncRoles($request->role);
        Myhelp::EscribirEnLog($this, 'UPDATE:users', 'usuario id:' . $user->id . ' | ' . $user->name . ' actualizado', false);
        return back()->with('success', __('app.label.updated_successfully', ['name' => $user->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user){
        $permissions = Myhelp::EscribirEnLog($this, 'DELETE:users');

        try {
            $user->delete();
            Myhelp::EscribirEnLog($this, 'DELETE:users', 'usuario id:' . $user->id . ' | ' . $user->name . ' borrado', false);
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $user->name]));
        } catch (\Throwable $th) {
            Myhelp::EscribirEnLog($this, 'DELETE:users', 'usuario id:' . $user->id . ' | ' . $user->name . ' fallo en el borrado:: ' . $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile(), false);
            return back()->with('error', __('app.label.deleted_error', ['name' => $user->name]) . $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile());
        }
    }

    public function destroyBulk(Request $request)
    {
        try {
            $user = User::whereIn('id', $request->id);
            $user->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.user')]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => count($request->id) . ' ' . __('app.label.user')]) . $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile());
        }
    }
    //FIN : STORE - UPDATE - DELETE

    public function subirexceles(){ //just  a view
        Myhelp::EscribirEnLog($this, ' materia');

        return Inertia::render('User/subirExceles', [
            'breadcrumbs'   => [['label' => __('app.label.user'), 'href' => route('user.index')]],
            'title'         => __('app.label.user'),
            'numUsuarios'   => count(User::all()) - 1,
            // 'UniversidadSelect'   => Universidad::all()
        ]);
    }

    public function todaBD(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new MultipleExport, 'CMA_Respaldo.xlsx');
    }



}
