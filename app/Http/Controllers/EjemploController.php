<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use App\Models\Permission;
use App\Models\User;
use Inertia\Inertia;

class EjemploController extends Controller
{
    public function ejemplo(){
        return Inertia::render('ejemplo', [
            'users' => (int)User::count(),
            'generico1' => (int)Inspeccion::count(),
            'permissions' => (int)Permission::count(),
        ]);
    }
}
