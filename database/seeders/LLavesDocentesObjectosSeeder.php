<?php

namespace Database\Seeders;

use App\Models\llaves;

class LLavesDocentesObjectosSeeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        $sala = 101;
        for ($i = 1; $i <= 3; $i++){
            for ($j = 1; $j < 8; $j++){
                llaves::create(['nombre' => 'a'.$sala]);
                $sala++;
            }
            $sala += 100;
        }
        llaves::create(['nombre' => 'a115']);
    }
}
/*
php artisan migrate --path=/database/migrations/nombre_de_la_migracion.php
php artisan db:seed --class=NombreDeLaSeeder
*/
