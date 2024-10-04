<?php

namespace Database\Seeders;

use App\helpers\CargosModelos;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'isSuper']);
        Permission::create(['name' => 'isAdmin']);

        $constantes = CargosModelos::CargosYModelos();
        
        foreach ($constantes['nombresDeCargos'] as $key => $value) {
            Permission::create(['name' => 'is'.$value]);
        }

        $crudCompleto = $constantes['crudCompleto'];
        foreach ($constantes['Models'] as $model) {
            foreach ($crudCompleto as $crud) {
                Permission::create(['name' => $crud . ' ' . $model]);
            }
        }
    }
}
