<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $CorreosAdmitidos = [
            'ajelof2+9@gmail.com',
            'alejofg2+9@gmail.com',
        ];
//        LogViewer::auth(function ($request) use ($CorreosAdmitidos) {
//            return $request->user() && in_array($request->user()->email, $CorreosAdmitidos);
//        });
        LogViewer::auth(function ($request) use ($CorreosAdmitidos) {
            return true; // Permite a cualquier usuario acceder temporalmente
        });
    }
}
