<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class docentes extends Model
{
    use HasFactory;

//    protected $connection = 'secundaria';
//    protected $table = 'Docente';
//$usuarios = DB::connection('secundaria')->table('usuarios')->get();

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'facultad',
        'cedula',
        'tipocontrato',
        'tipousuario',
        'simonid',
    ];

}
