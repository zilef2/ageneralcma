<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


/*
 * php artisan make:command MakeModelExperimental
 */
class CopyUserPages extends Command
{
    protected $signature = 'copy:u {folderName : Clase} {limpiar?} {depende? : depende de otra clase}';
    protected $description = 'Copia de la entidad generica';

    public function handle(): void
    {
        $foldernan = $this->argument('folderName');
        $limpiar = $this->argument('limpiar');

        $plantillaActual = 'generic';
        $mensajeA = 'La genericacion del componente: ';
        $mensajeExito = ' fue realizada con exito ';
        $mensajeFallo = ' fallo';

        $this->warn("Empezando make:model");
        Artisan::call('make:model', ['name' => $foldernan, '--all' => true]);
        $this->warn("Fin model");
        Artisan::call('copy:f');
        $this->warn("Fin copies");
        Artisan::call('lang:u '.$foldernan);
        $this->warn("Fin Lang");


        $RealizoVueConExito = $this->MakeVuePages($plantillaActual);
        $mensaje = $RealizoVueConExito ? $mensajeA.' Vuejs'.$mensajeExito
            : $mensajeA.' Vuejs'.$mensajeFallo;
        $this->info($mensaje);


        $RealizoControllerConExito = $this->MakeControllerPages($plantillaActual);
        $mensaje = $RealizoControllerConExito ? $mensajeA.'el controlador'.$mensajeExito
            : $mensajeA.' controlador '.$mensajeFallo;
        $this->info($mensaje);


        if($RealizoControllerConExito || $RealizoVueConExito)
            $this->replaceWordInFiles($plantillaActual,
            [
                'vue' => $RealizoVueConExito,
                'controller' => $RealizoControllerConExito
            ])
        ;


        $this->DoWebphp($foldernan);
        $this->DoAppLenguaje($foldernan);
        $this->DoSideBar($foldernan);
        if($limpiar){
            $this->info("Fin de la operacion. Se limpiará cache\n\n");
            $this->info('optimize: ');
            $this->info(Artisan::call('optimize'));
            $this->info('optimize_clear: ');
            $this->info(Artisan::call('optimize:clear'));
        }
    }


    private function MakeControllerPages($plantillaActual){
        $folderName = $this->argument('folderName');
        $folderMayus = ucfirst($folderName);
        $sourcePath = base_path('app/Http/Controllers/'.$plantillaActual.'sController.php');
        $destinationPath = base_path("app/Http/Controllers/".$folderMayus."sController.php");

        if (File::exists($destinationPath)) {
            $this->warn("La carpeta de destino '{$destinationPath}' ya existe.");
            return false;
        }
        File::copyDirectory($sourcePath, $destinationPath);
        $this->info("- ".$sourcePath);
        $this->info("- ".$destinationPath);

        return true;
    }

    private function MakeVuePages($plantillaActual){
        $folderName = $this->argument('folderName');

        $sourcePath = base_path('resources/js/Pages/'.$plantillaActual);
        $destinationPath = base_path("resources/js/Pages/{$folderName}");

        if (File::exists($destinationPath)) {
            $this->warn("La carpeta de destino '{$folderName}' ya existe.");
            return false;
        }
        File::copyDirectory($sourcePath, $destinationPath);
        return true;
    }

    private function replaceWordInFiles($oldWord,$permiteRemplazo){
        $newWord = $this->argument('folderName');
        $folderMayus = ucfirst($newWord);
        $files = File::allFiles(base_path("resources/js/Pages/{$newWord}"));
        $controller = base_path("app/Http/Controllers/{$folderMayus}".'Controller.php');
        $depende = $this->argument('depende') ?? '';

        $depende = $depende == '' || $depende == null ? 'no_nada' : $depende;

        if($permiteRemplazo['vue']){
            foreach ($files as $key => $file) {

                $content = file_get_contents($file);
                $content = str_replace(array($oldWord, ucfirst($oldWord),'geeneric'),//ojo aqui, es estatico
                                            [$newWord, $folderMayus,$folderMayus],
                                            $content
                );
                file_put_contents($file, $content);
            }
        }

        //reemplazo de controlador
        if($permiteRemplazo['controller']){
                $sourcePath = base_path('app/Http/Controllers/'.ucfirst($oldWord).'Controller.php');
                $content = file_get_contents($sourcePath);
                $content = str_replace(array($oldWord, 'dependex','deependex','geeneric'),//ojo aqui, es estatico
                                       array($newWord,  $depende ,ucfirst($depende),ucfirst($newWord)),
                                       $content
                );

                file_put_contents($controller, $content);
        }
    }

    private function DoFillable()
    {
        $directory = 'app/Models';
        $files = glob($directory . '/*.php');

        $fillable = "\n    protected \$fillable = [\n        'id',\n    ];\n";

        foreach ($files as $file) {

            $content = file_get_contents($file);

            if (strpos($content, 'protected $fillable') === false) {
                $content = preg_replace('/(use HasFactory;)/', "$1$fillable", $content);
                file_put_contents($file, $content);
                $this->info("Actualizado: $file\n");
            } else {
                $this->info("Ya existe en: $file\n");
            }
        }

        return true;
    }

    private function DoAppLenguaje($resource)
    {
        $directory = 'lang/es/app.php';
        $files = glob($directory);

        $insertable = "'$resource' => '$resource',\n\t\t//aquipues";
        $pattern = '/\/\/aquipues/';

        foreach ($files as $file){
            $content = file_get_contents($file);
            if (strpos($content, $pattern) === false) {
                $content2 = preg_replace($pattern, $insertable, $content);
//                $content2 = preg_replace($pattern, "$0$insertable", $content);
                file_put_contents($file, $content2);
                if($content == $content2)
                    $this->info("Language Actualizado: $file\n");
                else
                    $this->info("Language sin cambios: $file\n");
            } else {
                $this->error("No existe aquipues en: $file\n");
            }
        }

        return true;
    }


    private function DoWebphp($resource)
    {
        $directory = 'routes';
        $files = glob($directory . '/*.php');

        $insertable = "Route::resource(\"/$resource\", \\App\\Http\\Controllers\\" . ucfirst($resource) . "Controller::class);\n\t//aquipues";

        $pattern = '/\/\/aquipues/';

        foreach ($files as $file) {
            $content = file_get_contents($file);

            if (strpos($content, $pattern) === false) {
                $content2 = preg_replace($pattern, $insertable, $content);
//                $content2 = preg_replace($pattern, "$0$insertable", $content);
                file_put_contents($file, $content2);
                if($content == $content2)
                    $this->info("Routes Actualizado: $file\n");
                else
                    $this->info("Routes sin cambios: $file\n");
            } else {
                $this->error("No existe aquipues en: $file\n");
            }
        }

        return true;
    }

    private function DoSideBar($resource)
    {
        $directory = 'resources/js/Components/SideBarMenu.vue';
        $files = glob($directory);

        $insertable = "'".$resource."',\n\t//aquipuesSide";
        $pattern = '/\/\/aquipuesSide/';

        foreach ($files as $file) {
            $content = file_get_contents($file);

            if (strpos($content, $pattern) === false) {
                $content2 = preg_replace($pattern, $insertable, $content);
                //$content2 = preg_replace($pattern, "$0$insertable", $content);
                file_put_contents($file, $content2);
                if($content != $content2)
                    $this->info("SideBarMenu.vue Actualizado: $file\n");
                else
                    $this->info("SideBarMenu.vue sin cambios: $file\n"); //todo: revisar si ya existe
            } else {
                $this->error("No existe aquipues en: $file\n");
            }
        }

        return true;
    }

}
