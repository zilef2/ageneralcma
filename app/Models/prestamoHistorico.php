<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prestamoHistorico extends Model
{
    use HasFactory;
    protected $fillable = [//el id autoincremental
        'docenteId',
        'aulaId',
        'devuelto',
        'fecha',
        'horafin',
        'horainicio',
        'observaciones',
        'llave',
        'personalId',
        'cuentaId',

        'docente_nombre',
        'nombreAula',
        'nombreArticulo',
        'simonid',
    ];
}
