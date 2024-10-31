<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

//use Google\Service\SemanticTile\Area;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        // \App\Models\User::factory(10)->create();
        $this->call([
            ParametroSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,

            UserSeeder::class,
//            LLavesDocentesObjectosSeeder::class,
        ]);

//        $usuarios = DB::connection('secundaria')->table('usuarios')->get();
    }
}
