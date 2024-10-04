<?php

namespace App\helpers;

class CargosModelos {

    //JUST THIS PROJECT
    public static function CargosYModelos() {
        $crudSemiCompleto = ['update', 'read', 'create','download','sugerencia','aprobar','egreso','ingreso','firmar'];
        $crudCompleto = array_merge(['delete'], $crudSemiCompleto);
        
        //otros cargos NO_ADMIN
        $nombresDeCargos = [
            'Rector',//1
            'ViceRector', //2
            'Lider', //3
            'Vinculado', //4
            'Contratista', //5
        ];//recuerda userseeder, RoleSeeder
        $isSome = [];
        foreach ($nombresDeCargos as $key => $value) {
            $isSome[] = 'is' . $value;
        }
        $elcore = 'llaves';
        $Models = [
            'role',
            'permission',
            'user',
            'parametro',
            $elcore,
        ];
        
        return [
            'nombresDeCargos' => $nombresDeCargos,
            'Models' => $Models,
            'isSome' => $isSome,
            'core' => $elcore,
            'crudCompleto' => $crudCompleto,
            'crudSemiCompleto' => $crudSemiCompleto,
        ];
    }
}
?>
