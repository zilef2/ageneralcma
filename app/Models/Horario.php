<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $fillable = [
        'id' ,
        'docenteId' ,
        'aulaId' ,
        'horaInicio' ,
        'horaFin' ,
        'dia' ,
        'semestre',
        'simonid',
    ];

}
