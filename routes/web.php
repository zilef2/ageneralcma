<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\EjemploController;
use App\Http\Controllers\ParametrosController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\ZipController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


//Route::get('/', [FormularioController::class, 'welcome'])->name('welcome');
Route::get('/', function () { return redirect('/login'); });
//Route::get('/dashboard', [dashboardController::class, 'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/{nombredoc?}', [dashboardController::class, 'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/setLang/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return back();
})->name('setlang');

Route::get('/send', [\App\Http\Controllers\MailsMedios::class,'principal'])->name('mail.principal');
Route::get('/enviar', [\App\Http\Controllers\MailsMedios::class,'enviar'])->name('enviar');
Route::get('/enviarSoloAmi', [\App\Http\Controllers\MailsMedios::class,'enviarSoloAmi'])->name('enviarSoloAmi');
Route::get('/EnviarBitacoraManana', [\App\Http\Controllers\MailsMedios::class,'EnviarBitacoraManana'])->name('EnviarBitacoraManana');
Route::get('/achu', function () {
    $dash = new dashboardController();
    $mailData = $dash->GetPrestamosHoy();
    // dd($mailData);
    return view('emails.EstadomediosEnviar2')->with(['mailData' => $mailData]);
});

//,'handleErrorZilef'
//, 'verified'
Route::middleware(['auth'])->group(callback: function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/ejemplo', [EjemploController::class, 'ejemplo'])->name('ejemplo');

    Route::post('/user/destroy-bulk', [UserController::class, 'destroyBulk'])->name('user.destroy-bulk');


    Route::resource('/role', RoleController::class)->except('create', 'show', 'edit');
    Route::post('/role/destroy-bulk', [RoleController::class, 'destroyBulk'])->name('role.destroy-bulk');

    Route::resource('/permission', PermissionController::class)->except('create', 'show', 'edit');
    Route::post('/permission/destroy-bulk', [PermissionController::class, 'destroyBulk'])->name('permission.destroy-bulk');

    Route::resource('/parametro', ParametrosController::class);

    Route::get('/DB_info', [UserController::class,'todaBD']);
    Route::get('/DescompresionDespliegue/{esAmbientePruebas}', [ZipController::class, 'DescompresionDespliegue']);


    //<editor-fold desc="resources">
    Route::resource('/user', UserController::class);
	Route::resource("/docentes", \App\Http\Controllers\DocentesController::class);
	Route::resource("/llaves", \App\Http\Controllers\LlavesController::class);
	Route::resource("/objetos", \App\Http\Controllers\ObjetosController::class);
	Route::resource("/Horario", \App\Http\Controllers\HorarioController::class);
	Route::resource("/prestamoHistorico", \App\Http\Controllers\prestamoHistoricoController::class);
	Route::resource("/PrestamosAyer", \App\Http\Controllers\PrestamosAyerController::class);
	//aquipues
    //</editor-fold>


});


// <editor-fold desc="Artisan">
require __DIR__ . '/auth.php';
Route::get('/exception', function () {
    throw new Exception('Probando excepciones. La prueba ha concluido exitosamente.');
});

Route::get('/foo', function () {
    if (file_exists(public_path('storage'))) {
        return 'Ya existe';
    }
    App('files')->link(
        storage_path('App/public'),
        public_path('storage')
    );
    return 'Listo';
});

Route::get('/clear-c', function () {
    Artisan::call('optimize');
    Artisan::call('optimize:clear');
    return "Optimizacion finalizada";
    // throw new Exception('Optimizacion finalizada!');
});

Route::get('/tmantenimiento', function () {
    echo Artisan::call('down --secret="token-it"');
    return "Aplicación abajo: token-it";
});
Route::get('/Arriba', function () {
    echo Artisan::call('up');
    return "Aplicación funcionando";
});

//</editor-fold>
