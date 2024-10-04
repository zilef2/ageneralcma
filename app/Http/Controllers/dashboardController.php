<?php

namespace App\Http\Controllers;

use App\helpers\MyGlobalHelp;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use App\Models\Inspeccion;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;

class dashboardController extends Controller
{
    public function Dashboard()
    {
        $numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' | inspeccions create | '));


        return Inertia::render('Dashboard', [
            'users' => (int)User::count(),
            'roles' => (int) Role::count(),

            'rolesNameds' => Role::where('name', '<>', 'superadmin')->pluck('name'),

            'numberPermissions'   => $numberPermissions,
        ]);
    }
}
